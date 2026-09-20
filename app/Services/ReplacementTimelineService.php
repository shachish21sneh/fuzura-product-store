<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\ProductReplacement;
use App\Models\WarrantyRegistration;
use Carbon\Carbon;

class ReplacementTimelineService
{
    /**
     * Resolve complete timeline for any serial number in a product chain.
     */
    public function getTimeline(string $serialNumber): array
    {
        $serialNumber = trim($serialNumber);
        $product = Product::where('serial_number', $serialNumber)->first();

        // If product doesn't exist in master, check if it exists in sales or replacements
        if (!$product) {
            $saleCheck = ProductSale::where('product_serial_number', $serialNumber)->first();
            $repCheck = ProductReplacement::where('old_serial_number', $serialNumber)
                ->orWhere('new_serial_number', $serialNumber)->first();

            if (!$saleCheck && !$repCheck) {
                return [
                    'found' => false,
                    'searched_serial' => $serialNumber,
                    'message' => 'No Product Found',
                ];
            }
        }

        // Trace backwards to find the ROOT / ORIGINAL product in the chain
        $curr = $serialNumber;
        $visited = [];
        while ($prevRep = ProductReplacement::where('new_serial_number', $curr)->first()) {
            if (in_array($curr, $visited)) {
                break; // safeguard against cyclic loops
            }
            $visited[] = $curr;
            $curr = $prevRep->old_serial_number;
        }
        $rootSerial = $curr;

        // Fetch Root Product & Original Sale
        $rootProduct = Product::where('serial_number', $rootSerial)->first();
        $sale = ProductSale::where('product_serial_number', $rootSerial)->first();

        // If sale not found on rootSerial, check if any replacement has sale_id
        if (!$sale) {
            $anyRep = ProductReplacement::where('old_serial_number', $rootSerial)
                ->orWhere('new_serial_number', $rootSerial)->first();
            if ($anyRep && $anyRep->sale) {
                $sale = $anyRep->sale;
            }
        }

        // Trace forward from $rootSerial to build the ordered timeline
        $timeline = [];
        $chainSerials = [$rootSerial];

        // Root Node
        $rootNode = [
            'level' => 0,
            'type' => 'original',
            'title' => 'Original Product Sale',
            'serial_number' => $rootSerial,
            'model_number' => $rootProduct?->model_number ?? $sale?->product_model ?? 'N/A',
            'product' => $rootProduct,
            'dealer_name' => $sale?->dealer_name ?? 'N/A',
            'dealer_address' => $sale?->dealer_address ?? null,
            'customer_name' => $sale?->customer_name ?? 'N/A',
            'customer_mobile' => $sale?->customer_mobile ?? null,
            'customer_address' => $sale?->customer_address ?? null,
            'date' => $sale?->bill_date,
            'status' => $rootProduct?->status ?? 'sold',
            'remarks' => 'Original purchase recorded',
            'is_searched' => ($rootSerial === $serialNumber),
            'is_current_active' => false,
        ];

        $timeline[] = $rootNode;

        $step = 1;
        $forwardCurr = $rootSerial;
        $forwardVisited = [$rootSerial];

        while ($nextRep = ProductReplacement::where('old_serial_number', $forwardCurr)->first()) {
            if (in_array($nextRep->new_serial_number, $forwardVisited)) {
                break;
            }
            $forwardVisited[] = $nextRep->new_serial_number;
            $chainSerials[] = $nextRep->new_serial_number;

            $repProduct = Product::where('serial_number', $nextRep->new_serial_number)->first();

            $timeline[] = [
                'level' => $step,
                'type' => 'replacement',
                'title' => "Replacement #{$step}",
                'serial_number' => $nextRep->new_serial_number,
                'replaced_from' => $nextRep->old_serial_number,
                'model_number' => $repProduct?->model_number ?? $rootNode['model_number'],
                'product' => $repProduct,
                'dealer_name' => $nextRep->dealer_name ?? $sale?->dealer_name ?? 'N/A',
                'customer_name' => $nextRep->customer_name ?? $sale?->customer_name ?? 'N/A',
                'date' => $nextRep->replacement_date,
                'status' => $repProduct?->status ?? 'sold',
                'remarks' => $nextRep->remarks ?? 'Warranty replacement provided',
                'is_searched' => ($nextRep->new_serial_number === $serialNumber),
                'is_current_active' => false,
            ];

            $forwardCurr = $nextRep->new_serial_number;
            $step++;
        }

        // Mark the last node as the current active product
        $lastIndex = count($timeline) - 1;
        $timeline[$lastIndex]['is_current_active'] = true;
        $activeSerial = $timeline[$lastIndex]['serial_number'];
        $activeProduct = $timeline[$lastIndex]['product'];

        // Find Warranty Registration for any serial in the chain
        $registration = WarrantyRegistration::whereIn('product_serial_number', $chainSerials)
            ->latest()
            ->first();

        // Calculate Warranty Period & Expiry
        $warrantyPeriodYears = $rootProduct?->warranty_period_years 
            ?? $activeProduct?->warranty_period_years 
            ?? 1;

        $startDate = null;
        $endDate = null;
        $isExpired = false;
        $daysRemaining = 0;
        $warrantyStatus = 'not_sold';

        if ($sale || $registration) {
            $startDate = $registration?->warranty_start_date 
                ?? $sale?->bill_date 
                ?? ($registration?->purchase_date ? Carbon::parse($registration->purchase_date) : null);

            if ($startDate) {
                $startDate = Carbon::parse($startDate);
                $endDate = $registration?->warranty_end_date 
                    ?? $startDate->copy()->addYears($warrantyPeriodYears);
                $endDate = Carbon::parse($endDate);

                $today = Carbon::now()->startOfDay();
                $isExpired = $today->gt($endDate->startOfDay());
                $daysRemaining = $isExpired ? 0 : (int) $today->diffInDays($endDate->startOfDay());
                $warrantyStatus = $isExpired ? 'expired' : 'active';
            }
        } elseif ($product && $product->status === 'available') {
            $warrantyStatus = 'available_in_stock';
        }

        return [
            'found' => true,
            'searched_serial' => $serialNumber,
            'product' => $product ?? $rootProduct,
            'active_product' => $activeProduct,
            'sale' => $sale,
            'registration' => $registration,
            'timeline' => $timeline,
            'chain_serials' => $chainSerials,
            'root_serial' => $rootSerial,
            'active_serial' => $activeSerial,
            'has_replacements' => (count($timeline) > 1),
            'total_replacements' => count($timeline) - 1,
            'warranty_period_years' => $warrantyPeriodYears,
            'warranty_start_date' => $startDate?->format('d M Y'),
            'warranty_end_date' => $endDate?->format('d M Y'),
            'warranty_status' => $warrantyStatus,
            'is_expired' => $isExpired,
            'days_remaining' => $daysRemaining,
        ];
    }
}
