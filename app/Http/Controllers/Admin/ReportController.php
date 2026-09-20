<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReplacement;
use App\Models\ProductSale;
use App\Models\WarrantyRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'sales');
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->toDateString());

        $data = [];

        if ($type === 'sales') {
            $data = ProductSale::whereBetween('bill_date', [$fromDate, $toDate])
                ->latest('bill_date')
                ->paginate(20)
                ->withQueryString();
        } elseif ($type === 'warranties') {
            $data = WarrantyRegistration::whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ])
                ->latest()
                ->paginate(20)
                ->withQueryString();
        } elseif ($type === 'replacements') {
            $data = ProductReplacement::with(['oldProduct', 'newProduct', 'sale'])
                ->whereBetween('replacement_date', [$fromDate, $toDate])
                ->latest('replacement_date')
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.reports.index', compact('data', 'type', 'fromDate', 'toDate'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $type = $request->input('type', 'sales');
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->toDateString());

        $fileName = "report_{$type}_{$fromDate}_to_{$toDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function () use ($type, $fromDate, $toDate) {
            $handle = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($handle, ['ID', 'Bill Date', 'Serial Number', 'Model', 'Dealer', 'Customer', 'Mobile', 'Invoice #']);
                $records = ProductSale::whereBetween('bill_date', [$fromDate, $toDate])->latest('bill_date')->get();
                foreach ($records as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->bill_date->format('Y-m-d'),
                        $r->product_serial_number,
                        $r->product_model,
                        $r->dealer_name,
                        $r->customer_name,
                        $r->customer_mobile,
                        $r->invoice_number,
                    ]);
                }
            } elseif ($type === 'warranties') {
                fputcsv($handle, ['ID', 'Serial Number', 'Model', 'Customer Name', 'Customer Email', 'Mobile', 'Dealer', 'Purchase Date', 'Warranty Expiry', 'Status']);
                $records = WarrantyRegistration::whereBetween('created_at', [
                    Carbon::parse($fromDate)->startOfDay(),
                    Carbon::parse($toDate)->endOfDay()
                ])->latest()->get();
                foreach ($records as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->product_serial_number,
                        $r->product_model,
                        $r->customer_name,
                        $r->customer_email,
                        $r->customer_mobile,
                        $r->dealer_name,
                        $r->purchase_date->format('Y-m-d'),
                        $r->warranty_end_date->format('Y-m-d'),
                        $r->status,
                    ]);
                }
            } elseif ($type === 'replacements') {
                fputcsv($handle, ['ID', 'Replacement Date', 'Old Serial', 'New Serial', 'Customer', 'Dealer', 'Remarks']);
                $records = ProductReplacement::whereBetween('replacement_date', [$fromDate, $toDate])->latest('replacement_date')->get();
                foreach ($records as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->replacement_date->format('Y-m-d'),
                        $r->old_serial_number,
                        $r->new_serial_number,
                        $r->customer_name,
                        $r->dealer_name,
                        $r->remarks,
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}
