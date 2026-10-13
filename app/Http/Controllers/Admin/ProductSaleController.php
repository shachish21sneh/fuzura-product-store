<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductSale::with(['product', 'replacements']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_serial_number', 'like', "%{$search}%")
                  ->orWhere('product_model', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('dealer_name', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('bill_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('bill_date', '<=', $request->to_date);
        }

        if ($request->filled('assigned_to')) {
            $type = $request->assigned_to;
            if ($type === 'dealer') {
                $query->whereNotNull('dealer_name')
                      ->where('dealer_name', '!=', '')
                      ->where(function ($q) {
                          $q->whereNull('customer_name')->orWhere('customer_name', '');
                      });
            } elseif ($type === 'customer') {
                $query->whereNotNull('customer_name')
                      ->where('customer_name', '!=', '')
                      ->where(function ($q) {
                          $q->whereNull('dealer_name')->orWhere('dealer_name', '');
                      });
            } elseif ($type === 'both') {
                $query->whereNotNull('dealer_name')
                      ->where('dealer_name', '!=', '')
                      ->whereNotNull('customer_name')
                      ->where('customer_name', '!=', '');
            }
        }

        $sales = $query->latest('bill_date')->paginate(15)->withQueryString();
        $availableProducts = Product::available()->get();

        return view('admin.sales.index', compact('sales', 'availableProducts'));
    }

    public function store(Request $request)
    {
        $assignDealer = $request->boolean('assign_dealer');
        $assignCustomer = $request->boolean('assign_customer');

        // Fallback for legacy requests without explicit checkbox flags
        if (!$request->has('assign_dealer') && !$request->has('assign_customer')) {
            $assignDealer = $request->filled('dealer_name');
            $assignCustomer = $request->filled('customer_name') || $request->filled('customer_mobile');
        }

        if (!$assignDealer && !$assignCustomer) {
            $msg = 'Please select at least one recipient: Dealer and/or Customer.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg, 'errors' => ['assign_dealer' => [$msg]]], 422);
            }
            return back()->withErrors(['assign_dealer' => $msg])->withInput();
        }

        $rules = [
            'product_serial_number' => 'required|string|exists:products,serial_number',
            'bill_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:100',
            'assign_dealer' => 'nullable',
            'assign_customer' => 'nullable',
        ];

        if ($assignDealer) {
            $rules['dealer_name'] = 'required|string|max:150';
            $rules['dealer_address'] = 'nullable|string|max:500';
        } else {
            $rules['dealer_name'] = 'nullable|string|max:150';
            $rules['dealer_address'] = 'nullable|string|max:500';
        }

        if ($assignCustomer) {
            $rules['customer_name'] = 'required|string|max:150';
            $rules['customer_mobile'] = 'required|string|max:20';
            $rules['customer_address'] = 'nullable|string|max:500';
        } else {
            $rules['customer_name'] = 'nullable|string|max:150';
            $rules['customer_mobile'] = 'nullable|string|max:20';
            $rules['customer_address'] = 'nullable|string|max:500';
        }

        $validated = $request->validate($rules);

        $product = Product::where('serial_number', $validated['product_serial_number'])->firstOrFail();

        // Ensure product is available and not already sold
        if ($product->status !== 'available') {
            $msg = "Product with serial number {$product->serial_number} is currently '{$product->status}' and cannot be sold.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $assignedTo = ($assignDealer && $assignCustomer) ? 'both' : ($assignDealer ? 'dealer' : 'customer');

        DB::transaction(function () use ($validated, $product, $assignDealer, $assignCustomer, $assignedTo, &$sale) {
            $sale = ProductSale::create([
                'product_id' => $product->id,
                'product_model' => $product->model_number,
                'product_serial_number' => $product->serial_number,
                'bill_date' => $validated['bill_date'],
                'assigned_to' => $assignedTo,
                'dealer_name' => $assignDealer ? $validated['dealer_name'] : null,
                'dealer_address' => $assignDealer ? ($validated['dealer_address'] ?? null) : null,
                'customer_name' => $assignCustomer ? $validated['customer_name'] : null,
                'customer_mobile' => $assignCustomer ? $validated['customer_mobile'] : null,
                'customer_address' => $assignCustomer ? ($validated['customer_address'] ?? null) : null,
                'invoice_number' => $validated['invoice_number'] ?? null,
            ]);

            // Update product status to sold
            $product->update(['status' => 'sold']);

            $targetDesc = match ($assignedTo) {
                'both' => "to Dealer ({$sale->dealer_name}) & Customer ({$sale->customer_name})",
                'dealer' => "to Dealer ({$sale->dealer_name})",
                'customer' => "to Customer ({$sale->customer_name})",
                default => "",
            };

            ActivityLog::record('RECORD_SALE', "Recorded sale for serial {$product->serial_number} {$targetDesc}", $sale->toArray());
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale recorded successfully!',
                'sale' => $sale,
            ]);
        }

        return redirect()->route('admin.sales.index')->with('success', 'Sale recorded successfully.');
    }

    public function show($id)
    {
        $sale = ProductSale::with(['product', 'replacements.newProduct', 'warrantyRegistration'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'sale' => $sale,
            ]);
        }

        return view('admin.sales.show', compact('sale'));
    }

    public function destroy(Request $request, $id)
    {
        $sale = ProductSale::findOrFail($id);

        if ($sale->replacements()->exists()) {
            $msg = 'Cannot delete sale that has replacements associated with it.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        DB::transaction(function () use ($sale) {
            $product = $sale->product;
            if ($product) {
                $product->update(['status' => 'available']);
            }
            $sale->delete();
            ActivityLog::record('DELETE_SALE', "Deleted sale record ID: {$sale->id} for serial: {$sale->product_serial_number}");
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale deleted and product returned to stock.',
            ]);
        }

        return redirect()->route('admin.sales.index')->with('success', 'Sale deleted successfully.');
    }
}
