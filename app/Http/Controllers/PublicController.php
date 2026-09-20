<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSale;
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

        return view('public.home', compact('totalProducts', 'totalRegistered', 'totalSales'));
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
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        return back()->with('success', 'Thank you for reaching out! Our warranty support team will get back to you shortly.');
    }
}
