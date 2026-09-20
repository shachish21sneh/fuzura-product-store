<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\WarrantyRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerWarrantyController extends Controller
{
    public function create()
    {
        $customer = Auth::guard('customer')->user();
        $models = Product::select('model_number')->distinct()->pluck('model_number');

        return view('customer.products.register', compact('customer', 'models'));
    }

    public function checkSerial(Request $request)
    {
        $serial = trim($request->input('serial_number', ''));

        if (empty($serial)) {
            return response()->json(['valid' => false, 'message' => 'Please enter a serial number.'], 422);
        }

        $product = Product::where('serial_number', $serial)->first();

        if (!$product) {
            return response()->json([
                'valid' => false,
                'message' => 'Serial number not recognized in our database. Please double check your product label.',
            ]);
        }

        // Check if already registered
        $alreadyRegistered = WarrantyRegistration::where('product_serial_number', $serial)->exists();
        if ($alreadyRegistered) {
            return response()->json([
                'valid' => false,
                'message' => 'This product serial number has already been registered for warranty.',
            ]);
        }

        // Fetch sale record if available to prefill dealer info
        $sale = ProductSale::where('product_serial_number', $serial)->first();

        return response()->json([
            'valid' => true,
            'message' => 'Valid product serial number!',
            'model_number' => $product->model_number,
            'warranty_period_years' => $product->warranty_period_years,
            'dealer_name' => $sale?->dealer_name ?? '',
            'dealer_address' => $sale?->dealer_address ?? '',
            'bill_date' => $sale?->bill_date?->format('Y-m-d') ?? '',
        ]);
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'address' => 'nullable|string|max:500',
            'product_model' => 'required|string|max:100',
            'product_serial_number' => 'required|string|unique:warranty_registrations,product_serial_number|exists:products,serial_number',
            'dealer_name' => 'required|string|max:150',
            'dealer_address' => 'nullable|string|max:500',
            'purchase_date' => 'required|date|before_or_equal:today',
            'bill' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $product = Product::where('serial_number', $validated['product_serial_number'])->firstOrFail();

        // Calculate warranty dates
        $purchaseDate = Carbon::parse($validated['purchase_date']);
        $warrantyStart = $purchaseDate;
        $warrantyEnd = $purchaseDate->copy()->addYears($product->warranty_period_years);

        // Store bill file
        $billPath = null;
        if ($request->hasFile('bill')) {
            $billPath = $request->file('bill')->store('bills', 'public');
        }

        $registration = WarrantyRegistration::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'customer_name' => $validated['name'],
            'customer_mobile' => $validated['mobile'],
            'customer_email' => $validated['email'],
            'customer_address' => $validated['address'] ?? null,
            'product_model' => $product->model_number,
            'product_serial_number' => $product->serial_number,
            'dealer_name' => $validated['dealer_name'],
            'dealer_address' => $validated['dealer_address'] ?? null,
            'purchase_date' => $purchaseDate,
            'bill_path' => $billPath,
            'warranty_start_date' => $warrantyStart,
            'warranty_end_date' => $warrantyEnd,
            'status' => 'approved', // Auto-activated upon valid submission
        ]);

        ActivityLog::record(
            'WARRANTY_REGISTERED',
            "Warranty registered for serial {$product->serial_number} by {$customer->email}",
            $registration->toArray()
        );

        return redirect()->route('customer.products.index')
            ->with('success', 'Product registered successfully! Your warranty has been activated.');
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $registrations = WarrantyRegistration::with(['product', 'sale'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view('customer.products.index', compact('registrations'));
    }

    public function certificate($id)
    {
        $customer = Auth::guard('customer')->user();
        $warranty = WarrantyRegistration::with(['product', 'sale'])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return view('customer.products.certificate', compact('warranty'));
    }

    public function viewBill($id)
    {
        $customer = Auth::guard('customer')->user();
        $warranty = WarrantyRegistration::where('customer_id', $customer->id)->findOrFail($id);

        if (!$warranty->bill_path || !Storage::disk('public')->exists($warranty->bill_path)) {
            return back()->with('error', 'Bill invoice not found.');
        }

        return Storage::disk('public')->response($warranty->bill_path);
    }
}
