<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('model_number', 'like', "%{$search}%")
                  ->orWhere('spare_vendor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('model_number')) {
            $query->where('model_number', $request->model_number);
        }

        $sortField = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $allowedSorts = ['id', 'model_number', 'serial_number', 'manufacturing_date', 'warranty_period_years', 'status', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $products = $query->paginate(15)->withQueryString();
        $models = Product::select('model_number')->distinct()->pluck('model_number');

        return view('admin.products.index', compact('products', 'models'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_number' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:products,serial_number',
            'manufacturing_date' => 'required|date',
            'warranty_period_years' => 'required|integer|min:1|max:10',
            'spare_details' => 'nullable|string|max:1000',
            'spare_vendor' => 'nullable|string|max:150',
            'status' => 'required|in:available,sold,replaced,scrapped',
        ]);

        $product = Product::create($validated);

        ActivityLog::record('CREATE_PRODUCT', "Created product {$product->serial_number} ({$product->model_number})", $product->toArray());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'product' => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::with(['sale', 'warrantyRegistrations', 'outgoingReplacements', 'incomingReplacements'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'product' => $product,
            ]);
        }

        return view('admin.products.show', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'model_number' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:products,serial_number,' . $product->id,
            'manufacturing_date' => 'required|date',
            'warranty_period_years' => 'required|integer|min:1|max:10',
            'spare_details' => 'nullable|string|max:1000',
            'spare_vendor' => 'nullable|string|max:150',
            'status' => 'required|in:available,sold,replaced,scrapped',
        ]);

        $product->update($validated);

        ActivityLog::record('UPDATE_PRODUCT', "Updated product {$product->serial_number}", $product->toArray());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Cannot delete if product was sold or replaced
        if ($product->sale()->exists() || $product->outgoingReplacements()->exists() || $product->incomingReplacements()->exists()) {
            $msg = 'Cannot delete product with existing sales or replacement history.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $serial = $product->serial_number;
        $product->delete();

        ActivityLog::record('DELETE_PRODUCT', "Deleted product {$serial}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function getAvailableSerials(Request $request)
    {
        $model = $request->query('model');
        $query = Product::where('status', 'available');

        if (!empty($model)) {
            $query->where('model_number', $model);
        }

        $products = $query->select('id', 'serial_number', 'model_number', 'warranty_period_years')->get();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function getProductBySerial(string $serial)
    {
        $product = Product::where('serial_number', $serial)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found in master.'], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
}
