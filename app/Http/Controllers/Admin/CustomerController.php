<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('warrantyRegistrations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with(['warrantyRegistrations.product'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        return view('admin.customers.show', compact('customer'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->update(['status' => $newStatus]);

        ActivityLog::record('CUSTOMER_STATUS_CHANGE', "Toggled status of customer {$customer->name} to {$newStatus}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Customer status updated to {$newStatus}!",
                'status' => $newStatus,
            ]);
        }

        return back()->with('success', "Customer status changed to {$newStatus}.");
    }
}
