<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\WarrantyRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $today = Carbon::now()->startOfDay();

        $registrations = WarrantyRegistration::with('product')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        $totalRegistered = $registrations->count();

        $activeWarranties = $registrations->filter(function ($reg) use ($today) {
            return $reg->status === 'approved' && $reg->warranty_end_date->gte($today);
        })->count();

        $expiredWarranties = $registrations->filter(function ($reg) use ($today) {
            return $reg->status === 'approved' && $reg->warranty_end_date->lt($today);
        })->count();

        $pendingRegistrations = $registrations->where('status', 'pending')->count();

        $recentRegistrations = $registrations->take(5);

        return view('customer.dashboard', compact(
            'customer',
            'totalRegistered',
            'activeWarranties',
            'expiredWarranties',
            'pendingRegistrations',
            'recentRegistrations'
        ));
    }
}
