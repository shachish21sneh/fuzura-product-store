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

        $sales = $query->latest('bill_date')->paginate(15)->withQueryString();
        $availableProducts = Product::available()->get();

        return view('admin.sales.index', compact('sales', 'availableProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_serial_number' => 'required|string|exists:products,serial_number',
            'bill_date' => 'required|date',
            'dealer_name' => 'required|string|max:150',
            'dealer_address' => 'nullable|string|max:500',
            'customer_name' => 'required|string|max:150',
            'customer_mobile' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'invoice_number' => 'nullable|string|max:100',
        ]);

        $product = Product::where('serial_number', $validated['product_serial_number'])->firstOrFail();

        // Ensure product is available and not already sold
        if ($product->status !== 'available') {
            $msg = "Product with serial number {$product->serial_number} is currently '{$product->status}' and cannot be sold.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        DB::transaction(function () use ($validated, $product, &$sale) {
            $sale = ProductSale::create([
                'product_id' => $product->id,
                'product_model' => $product->model_number,
                'product_serial_number' => $product->serial_number,
                'bill_date' => $validated['bill_date'],
                'dealer_name' => $validated['dealer_name'],
                'dealer_address' => $validated['dealer_address'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_mobile' => $validated['customer_mobile'],
                'customer_address' => $validated['customer_address'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
            ]);

            // Update product status to sold
            $product->update(['status' => 'sold']);

            ActivityLog::record('RECORD_SALE', "Recorded sale for serial {$product->serial_number} to {$sale->customer_name}", $sale->toArray());
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
