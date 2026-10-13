<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealerController extends Controller
{
    public function index(Request $request)
    {
        $query = Dealer::query()->withCount('sales');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $dealers = $query->latest()->paginate(15)->withQueryString();
        $totalDealers = Dealer::count();
        $activeDealers = Dealer::where('is_active', true)->count();

        return view('admin.dealers.index', compact('dealers', 'totalDealers', 'activeDealers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:dealers,name',
            'code' => 'nullable|string|max:50|unique:dealers,code',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $dealer = Dealer::create($validated);

        ActivityLog::record(
            'DEALER_CREATED',
            "New authorized dealer created: {$dealer->name} ({$dealer->code})",
            'dealer',
            $dealer->id
        );

        return redirect()->route('admin.dealers.index')->with('success', "Dealer '{$dealer->name}' registered successfully.");
    }

    public function show($id)
    {
        $dealer = Dealer::withCount('sales')->findOrFail($id);
        return response()->json(['success' => true, 'dealer' => $dealer]);
    }

    public function update(Request $request, $id)
    {
        $dealer = Dealer::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('dealers')->ignore($dealer->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('dealers')->ignore($dealer->id)],
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $dealer->update($validated);

        ActivityLog::record(
            'DEALER_UPDATED',
            "Dealer profile updated: {$dealer->name}",
            'dealer',
            $dealer->id
        );

        return redirect()->route('admin.dealers.index')->with('success', "Dealer '{$dealer->name}' updated successfully.");
    }

    public function toggleStatus($id)
    {
        $dealer = Dealer::findOrFail($id);
        $dealer->is_active = !$dealer->is_active;
        $dealer->save();

        ActivityLog::record(
            'DEALER_STATUS_TOGGLED',
            "Dealer '{$dealer->name}' status changed to " . ($dealer->is_active ? 'Active' : 'Inactive'),
            'dealer',
            $dealer->id
        );

        return back()->with('success', "Dealer '{$dealer->name}' status updated.");
    }

    public function destroy($id)
    {
        $dealer = Dealer::withCount('sales')->findOrFail($id);

        if ($dealer->sales_count > 0) {
            return back()->with('error', "Cannot delete dealer '{$dealer->name}' because they have {$dealer->sales_count} associated sales records. You may deactivate the dealer instead.");
        }

        $dealerName = $dealer->name;
        $dealer->delete();

        ActivityLog::record(
            'DEALER_DELETED',
            "Dealer deleted: {$dealerName}",
            'dealer',
            $id
        );

        return redirect()->route('admin.dealers.index')->with('success', "Dealer '{$dealerName}' deleted successfully.");
    }
}
