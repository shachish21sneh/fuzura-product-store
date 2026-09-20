<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReplacement;
use App\Models\ProductSale;
use App\Models\WarrantyRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->startOfDay();

        $totalProducts = Product::count();
        $totalSoldProducts = ProductSale::count();
        $totalCustomers = Customer::count();
        $totalRegistered = WarrantyRegistration::where('status', 'approved')->count();
        $pendingRegistrations = WarrantyRegistration::where('status', 'pending')->count();
        $replacementProducts = ProductReplacement::count();

        $activeWarranty = WarrantyRegistration::where('status', 'approved')
            ->where('warranty_end_date', '>=', $today)
            ->count();

        $expiredWarranty = WarrantyRegistration::where('status', 'approved')
            ->where('warranty_end_date', '<', $today)
            ->count();

        $recentActivities = ActivityLog::latest()->take(8)->get();
        $recentSales = ProductSale::with('product')->latest()->take(5)->get();
        $recentRegistrations = WarrantyRegistration::latest()->take(5)->get();

        // Monthly Sales (last 6 months)
        $monthlySales = [];
        $monthlyRegistrations = [];
        $monthLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthLabel = $month->format('M Y');
            $monthLabels[] = $monthLabel;

            $salesCount = ProductSale::whereYear('bill_date', $month->year)
                ->whereMonth('bill_date', $month->month)
                ->count();
            $monthlySales[] = $salesCount;

            $regCount = WarrantyRegistration::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyRegistrations[] = $regCount;
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalSoldProducts',
            'totalCustomers',
            'totalRegistered',
            'pendingRegistrations',
            'replacementProducts',
            'activeWarranty',
            'expiredWarranty',
            'recentActivities',
            'recentSales',
            'recentRegistrations',
            'monthLabels',
            'monthlySales',
            'monthlyRegistrations'
        ));
    }
}
