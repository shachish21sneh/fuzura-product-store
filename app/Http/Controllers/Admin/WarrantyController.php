<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\WarrantyRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {
        $query = WarrantyRegistration::with(['product', 'customer', 'sale']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_serial_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('dealer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warranties = $query->latest()->paginate(15)->withQueryString();

        return view('admin.warranties.index', compact('warranties'));
    }

    public function show($id)
    {
        $warranty = WarrantyRegistration::with(['product', 'customer', 'sale'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'warranty' => $warranty]);
        }

        return view('admin.warranties.show', compact('warranty'));
    }

    public function updateStatus(Request $request, $id)
    {
        $warranty = WarrantyRegistration::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,pending,rejected',
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        $warranty->update($validated);

        ActivityLog::record(
            'WARRANTY_STATUS_UPDATE',
            "Updated warranty status to '{$warranty->status}' for serial {$warranty->product_serial_number}",
            $warranty->toArray()
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Warranty registration status updated!',
                'warranty' => $warranty,
            ]);
        }

        return back()->with('success', 'Warranty status updated successfully.');
    }

    public function downloadBill($id)
    {
        $warranty = WarrantyRegistration::findOrFail($id);

        if (!$warranty->bill_path || !Storage::disk('public')->exists($warranty->bill_path)) {
            return back()->with('error', 'Invoice bill file not found on storage.');
        }

        return Storage::disk('public')->download($warranty->bill_path);
    }
}
