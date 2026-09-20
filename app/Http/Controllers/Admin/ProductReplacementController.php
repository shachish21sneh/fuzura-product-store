<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductReplacement;
use App\Models\ProductSale;
use App\Services\ReplacementTimelineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReplacementController extends Controller
{
    protected ReplacementTimelineService $timelineService;

    public function __construct(ReplacementTimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function index(Request $request)
    {
        $query = ProductReplacement::with(['sale', 'oldProduct', 'newProduct']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('old_serial_number', 'like', "%{$search}%")
                  ->orWhere('new_serial_number', 'like', "%{$search}%")
                  ->orWhere('dealer_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $replacements = $query->latest('replacement_date')->paginate(15)->withQueryString();
        $availableProducts = Product::available()->get();

        return view('admin.replacements.index', compact('replacements', 'availableProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:product_sales,id',
            'old_serial_number' => 'required|string|exists:products,serial_number',
            'new_serial_number' => 'required|string|different:old_serial_number|exists:products,serial_number',
            'replacement_date' => 'required|date',
            'dealer_name' => 'nullable|string|max:150',
            'customer_name' => 'nullable|string|max:150',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $sale = ProductSale::findOrFail($validated['sale_id']);
        $oldProduct = Product::where('serial_number', $validated['old_serial_number'])->firstOrFail();
        $newProduct = Product::where('serial_number', $validated['new_serial_number'])->firstOrFail();

        // New product must be available in stock
        if ($newProduct->status !== 'available') {
            $msg = "Selected replacement product ({$newProduct->serial_number}) is not available (status: {$newProduct->status}).";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        DB::transaction(function () use ($validated, $sale, $oldProduct, $newProduct, &$replacement) {
            $replacement = ProductReplacement::create([
                'sale_id' => $sale->id,
                'old_product_id' => $oldProduct->id,
                'old_serial_number' => $oldProduct->serial_number,
                'new_product_id' => $newProduct->id,
                'new_serial_number' => $newProduct->serial_number,
                'dealer_name' => $validated['dealer_name'] ?? $sale->dealer_name,
                'customer_name' => $validated['customer_name'] ?? $sale->customer_name,
                'replacement_date' => $validated['replacement_date'],
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Mark old product as replaced
            $oldProduct->update(['status' => 'replaced']);

            // Mark new product as sold (now in use by customer)
            $newProduct->update(['status' => 'sold']);

            ActivityLog::record(
                'PRODUCT_REPLACED',
                "Replaced product {$oldProduct->serial_number} with {$newProduct->serial_number} for sale #{$sale->id}",
                $replacement->toArray()
            );
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product replacement successfully registered!',
                'replacement' => $replacement,
            ]);
        }

        return redirect()->route('admin.replacements.index')->with('success', 'Product replacement recorded successfully.');
    }

    public function showTimeline(string $serialNumber)
    {
        $data = $this->timelineService->getTimeline($serialNumber);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return view('admin.replacements.timeline', compact('data'));
    }

    public function getEligibleNewProducts(Request $request)
    {
        $oldSerial = $request->query('old_serial');
        $oldProduct = Product::where('serial_number', $oldSerial)->first();

        $query = Product::where('status', 'available');

        // Prefer same model if available
        if ($oldProduct) {
            $query->where('model_number', $oldProduct->model_number);
        }

        $available = $query->get();

        // If no same model in stock, fetch any available
        if ($available->isEmpty()) {
            $available = Product::where('status', 'available')->get();
        }

        return response()->json([
            'success' => true,
            'products' => $available,
        ]);
    }
}
