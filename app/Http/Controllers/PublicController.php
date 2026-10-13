<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\ProductReplacement;
use App\Models\ProductSale;
use App\Models\Setting;
use App\Models\WarrantyRegistration;
use App\Services\ReplacementTimelineService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    protected ReplacementTimelineService $timelineService;

    public function __construct(ReplacementTimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function home()
    {
        $totalProducts = Product::count();
        $totalRegistered = WarrantyRegistration::where('status', 'approved')->count();
        $totalSales = ProductSale::count();
        $totalDealers = Dealer::active()->count();

        // 1. Dynamic Demo Serials (Admin-configured OR auto-detected from live database)
        $customDemoSetting = setting('demo_serials');
        $demoSerials = [];

        if (!empty($customDemoSetting)) {
            $serials = array_filter(array_map('trim', explode(',', $customDemoSetting)));
            foreach ($serials as $sn) {
                $demoSerials[] = [
                    'serial' => $sn,
                    'label' => 'Demo Serial',
                    'icon' => 'fas fa-barcode text-primary',
                ];
            }
        }

        if (empty($demoSerials)) {
            // Auto-detect real live serials from database
            // A. Serial in a replacement chain
            $chainSerial = ProductReplacement::latest()->value('old_serial_number');
            if ($chainSerial) {
                $demoSerials[] = [
                    'serial' => $chainSerial,
                    'label' => 'Chain Replaced',
                    'icon' => 'fas fa-repeat text-warning',
                ];
            }

            // B. Active registered unit
            $registeredSerial = WarrantyRegistration::where('status', 'approved')->latest()->value('product_serial_number');
            if ($registeredSerial && $registeredSerial !== $chainSerial) {
                $demoSerials[] = [
                    'serial' => $registeredSerial,
                    'label' => 'Active Warranty',
                    'icon' => 'fas fa-circle-check text-success',
                ];
            }

            // C. Verified sold unit
            $saleSerial = ProductSale::whereNotIn('product_serial_number', array_column($demoSerials, 'serial'))->latest('bill_date')->value('product_serial_number');
            if ($saleSerial) {
                $demoSerials[] = [
                    'serial' => $saleSerial,
                    'label' => 'Verified Sale',
                    'icon' => 'fas fa-shield text-info',
                ];
            }

            // D. In-stock available unit
            $stockSerial = Product::available()->whereNotIn('serial_number', array_column($demoSerials, 'serial'))->latest()->value('serial_number');
            if ($stockSerial) {
                $demoSerials[] = [
                    'serial' => $stockSerial,
                    'label' => 'In Catalog',
                    'icon' => 'fas fa-box text-secondary',
                ];
            }

            // Fallback defaults if catalog is empty
            if (empty($demoSerials)) {
                $demoSerials = [
                    ['serial' => 'FZ-SN-1002', 'label' => 'Chain Step', 'icon' => 'fas fa-repeat text-warning'],
                    ['serial' => 'FZ-SN-1004', 'label' => 'Active Unit', 'icon' => 'fas fa-circle-check text-success'],
                    ['serial' => 'FZ-SN-2001', 'label' => 'Active', 'icon' => 'fas fa-shield text-info'],
                ];
            }
        }

        // Live recent pipeline activity
        $recentReplacements = ProductReplacement::latest('replacement_date')->take(4)->get();

        return view('public.home', compact(
            'totalProducts',
            'totalRegistered',
            'totalSales',
            'totalDealers',
            'demoSerials',
            'recentReplacements'
        ));
    }

    public function search(Request $request)
    {
        $serial = $request->query('serial_number');
        $result = null;

        if (!empty($serial)) {
            $result = $this->timelineService->getTimeline($serial);
        }

        return view('public.search', [
            'searchedSerial' => $serial,
            'result' => $result,
        ]);
    }

    public function searchAjax(Request $request)
    {
        $serial = $request->input('serial_number');
        if (empty($serial)) {
            return response()->json(['success' => false, 'message' => 'Please enter a serial number.'], 422);
        }

        $result = $this->timelineService->getTimeline($serial);
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1500',
        ]);

        ContactInquiry::create($validated);

        return back()->with('success', 'Thank you for reaching out! Your support ticket has been received and our warranty team will respond promptly.');
    }
}
