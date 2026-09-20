<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\ProductReplacement;
use App\Models\WarrantyRegistration;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@fuzura.com'],
            [
                'name' => 'Fuzura Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+1 (800) 555-0199',
            ]
        );

        // 2. Seed Customers
        $customer1 = Customer::updateOrCreate(
            ['email' => 'john.doe@example.com'],
            [
                'name' => 'John Doe',
                'mobile' => '+1-555-0144',
                'address' => '742 Evergreen Terrace, Springfield, IL',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        $customer2 = Customer::updateOrCreate(
            ['email' => 'jane.smith@example.com'],
            [
                'name' => 'Jane Smith',
                'mobile' => '+1-555-0288',
                'address' => '1007 Mountain View Way, Austin, TX',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        $customer3 = Customer::updateOrCreate(
            ['email' => 'michael.brown@example.com'],
            [
                'name' => 'Michael Brown',
                'mobile' => '+1-555-0377',
                'address' => '42 Ocean Drive, Miami, FL',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        // 3. Seed Products
        $productsData = [
            // FZ-PRO-500 chain (Model with 3 years warranty)
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1001', 'manufacturing_date' => Carbon::now()->subMonths(20)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'replaced'],
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1002', 'manufacturing_date' => Carbon::now()->subMonths(14)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'replaced'],
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1003', 'manufacturing_date' => Carbon::now()->subMonths(8)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'replaced'],
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1004', 'manufacturing_date' => Carbon::now()->subMonths(3)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'sold'],
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1005', 'manufacturing_date' => Carbon::now()->subMonths(2)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'available'],
            ['model_number' => 'FZ-PRO-500', 'serial_number' => 'FZ-SN-1006', 'manufacturing_date' => Carbon::now()->subMonths(1)->toDateString(), 'warranty_period_years' => 3, 'spare_details' => 'Thermal sensor, Copper heatsink, 12V Fan', 'spare_vendor' => 'Precision Tech Ltd', 'status' => 'available'],

            // FZ-ECO-200 (Model with 2 years warranty)
            ['model_number' => 'FZ-ECO-200', 'serial_number' => 'FZ-SN-2001', 'manufacturing_date' => Carbon::now()->subMonths(7)->toDateString(), 'warranty_period_years' => 2, 'spare_details' => 'Micro controller, Power relay 5V', 'spare_vendor' => 'Acro Micro Systems', 'status' => 'sold'],
            ['model_number' => 'FZ-ECO-200', 'serial_number' => 'FZ-SN-2002', 'manufacturing_date' => Carbon::now()->subMonths(4)->toDateString(), 'warranty_period_years' => 2, 'spare_details' => 'Micro controller, Power relay 5V', 'spare_vendor' => 'Acro Micro Systems', 'status' => 'sold'],
            ['model_number' => 'FZ-ECO-200', 'serial_number' => 'FZ-SN-2003', 'manufacturing_date' => Carbon::now()->subMonths(2)->toDateString(), 'warranty_period_years' => 2, 'spare_details' => 'Micro controller, Power relay 5V', 'spare_vendor' => 'Acro Micro Systems', 'status' => 'available'],
            ['model_number' => 'FZ-ECO-200', 'serial_number' => 'FZ-SN-2004', 'manufacturing_date' => Carbon::now()->subMonths(1)->toDateString(), 'warranty_period_years' => 2, 'spare_details' => 'Micro controller, Power relay 5V', 'spare_vendor' => 'Acro Micro Systems', 'status' => 'available'],

            // FZ-MAX-900 (Enterprise model with 5 years warranty, including an expired one for testing)
            ['model_number' => 'FZ-MAX-900', 'serial_number' => 'FZ-SN-3001', 'manufacturing_date' => Carbon::now()->subYears(6)->toDateString(), 'warranty_period_years' => 5, 'spare_details' => 'Heavy duty transformer, Dual MOSFET', 'spare_vendor' => 'Titan Industrial Corp', 'status' => 'sold'],
            ['model_number' => 'FZ-MAX-900', 'serial_number' => 'FZ-SN-3002', 'manufacturing_date' => Carbon::now()->subDays(20)->toDateString(), 'warranty_period_years' => 5, 'spare_details' => 'Heavy duty transformer, Dual MOSFET', 'spare_vendor' => 'Titan Industrial Corp', 'status' => 'sold'],
            ['model_number' => 'FZ-MAX-900', 'serial_number' => 'FZ-SN-3003', 'manufacturing_date' => Carbon::now()->subDays(15)->toDateString(), 'warranty_period_years' => 5, 'spare_details' => 'Heavy duty transformer, Dual MOSFET', 'spare_vendor' => 'Titan Industrial Corp', 'status' => 'available'],
            ['model_number' => 'FZ-MAX-900', 'serial_number' => 'FZ-SN-3004', 'manufacturing_date' => Carbon::now()->subDays(10)->toDateString(), 'warranty_period_years' => 5, 'spare_details' => 'Heavy duty transformer, Dual MOSFET', 'spare_vendor' => 'Titan Industrial Corp', 'status' => 'available'],
            ['model_number' => 'FZ-MAX-900', 'serial_number' => 'FZ-SN-3005', 'manufacturing_date' => Carbon::now()->subDays(5)->toDateString(), 'warranty_period_years' => 5, 'spare_details' => 'Heavy duty transformer, Dual MOSFET', 'spare_vendor' => 'Titan Industrial Corp', 'status' => 'available'],
        ];

        foreach ($productsData as $data) {
            Product::updateOrCreate(['serial_number' => $data['serial_number']], $data);
        }

        // 4. Seed Product Sales
        $p1001 = Product::where('serial_number', 'FZ-SN-1001')->first();
        $sale1 = ProductSale::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-1001'],
            [
                'product_id' => $p1001->id,
                'product_model' => $p1001->model_number,
                'bill_date' => Carbon::now()->subMonths(18)->toDateString(),
                'dealer_name' => 'Apex Electronics Tech',
                'dealer_address' => 'Suite 400, Silicon Galleria, Chicago, IL',
                'customer_name' => 'John Doe',
                'customer_mobile' => '+1-555-0144',
                'customer_address' => '742 Evergreen Terrace, Springfield, IL',
                'invoice_number' => 'INV-2025-8810',
            ]
        );

        $p2001 = Product::where('serial_number', 'FZ-SN-2001')->first();
        $sale2 = ProductSale::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-2001'],
            [
                'product_id' => $p2001->id,
                'product_model' => $p2001->model_number,
                'bill_date' => Carbon::now()->subMonths(6)->toDateString(),
                'dealer_name' => 'Metro Digital Mart',
                'dealer_address' => '420 Commercial Ave, Austin, TX',
                'customer_name' => 'Jane Smith',
                'customer_mobile' => '+1-555-0288',
                'customer_address' => '1007 Mountain View Way, Austin, TX',
                'invoice_number' => 'INV-2026-1044',
            ]
        );

        $p2002 = Product::where('serial_number', 'FZ-SN-2002')->first();
        $sale3 = ProductSale::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-2002'],
            [
                'product_id' => $p2002->id,
                'product_model' => $p2002->model_number,
                'bill_date' => Carbon::now()->subMonths(2)->toDateString(),
                'dealer_name' => 'Global Supplies Inc',
                'dealer_address' => '88 Biscayne Blvd, Miami, FL',
                'customer_name' => 'Michael Brown',
                'customer_mobile' => '+1-555-0377',
                'customer_address' => '42 Ocean Drive, Miami, FL',
                'invoice_number' => 'INV-2026-3021',
            ]
        );

        $p3001 = Product::where('serial_number', 'FZ-SN-3001')->first();
        $sale4 = ProductSale::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-3001'],
            [
                'product_id' => $p3001->id,
                'product_model' => $p3001->model_number,
                'bill_date' => Carbon::now()->subYears(6)->toDateString(),
                'dealer_name' => 'Summit Hardware Hub',
                'dealer_address' => '12 Central Plaza, Denver, CO',
                'customer_name' => 'John Doe',
                'customer_mobile' => '+1-555-0144',
                'customer_address' => '742 Evergreen Terrace, Springfield, IL',
                'invoice_number' => 'INV-2020-0092',
            ]
        );

        $p3002 = Product::where('serial_number', 'FZ-SN-3002')->first();
        $sale5 = ProductSale::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-3002'],
            [
                'product_id' => $p3002->id,
                'product_model' => $p3002->model_number,
                'bill_date' => Carbon::now()->subDays(10)->toDateString(),
                'dealer_name' => 'Nexus Retailers',
                'dealer_address' => '500 Tech Parkway, Dallas, TX',
                'customer_name' => 'Jane Smith',
                'customer_mobile' => '+1-555-0288',
                'customer_address' => '1007 Mountain View Way, Austin, TX',
                'invoice_number' => 'INV-2026-9901',
            ]
        );

        // 5. Seed Multi-Level Product Replacements (3 Levels Chain)
        // Original: FZ-SN-1001 -> Replacement 1: FZ-SN-1002
        $p1002 = Product::where('serial_number', 'FZ-SN-1002')->first();
        ProductReplacement::updateOrCreate(
            ['old_serial_number' => 'FZ-SN-1001', 'new_serial_number' => 'FZ-SN-1002'],
            [
                'sale_id' => $sale1->id,
                'old_product_id' => $p1001->id,
                'new_product_id' => $p1002->id,
                'dealer_name' => 'Apex Electronics Tech',
                'customer_name' => 'John Doe',
                'replacement_date' => Carbon::now()->subMonths(12)->toDateString(),
                'remarks' => 'Internal PCB diode failure during power spike. Replaced under standard warranty.',
            ]
        );

        // Replacement 1: FZ-SN-1002 -> Replacement 2: FZ-SN-1003
        $p1003 = Product::where('serial_number', 'FZ-SN-1003')->first();
        ProductReplacement::updateOrCreate(
            ['old_serial_number' => 'FZ-SN-1002', 'new_serial_number' => 'FZ-SN-1003'],
            [
                'sale_id' => $sale1->id,
                'old_product_id' => $p1002->id,
                'new_product_id' => $p1003->id,
                'dealer_name' => 'Apex Electronics Tech',
                'customer_name' => 'John Doe',
                'replacement_date' => Carbon::now()->subMonths(6)->toDateString(),
                'remarks' => 'Control unit display backlight failed. Replacement approved.',
            ]
        );

        // Replacement 2: FZ-SN-1003 -> Current Active: FZ-SN-1004
        $p1004 = Product::where('serial_number', 'FZ-SN-1004')->first();
        ProductReplacement::updateOrCreate(
            ['old_serial_number' => 'FZ-SN-1003', 'new_serial_number' => 'FZ-SN-1004'],
            [
                'sale_id' => $sale1->id,
                'old_product_id' => $p1003->id,
                'new_product_id' => $p1004->id,
                'dealer_name' => 'Apex Electronics Tech',
                'customer_name' => 'John Doe',
                'replacement_date' => Carbon::now()->subMonths(1)->toDateString(),
                'remarks' => 'Customer reported connector loose pin. Upgraded to latest batch FZ-SN-1004 unit.',
            ]
        );

        // 6. Ensure sample bill exists in storage
        if (!Storage::disk('public')->exists('bills/sample_bill.png')) {
            // Generate a lightweight demo invoice image (1x1 PNG or text)
            $samplePng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
            Storage::disk('public')->put('bills/sample_bill.png', $samplePng);
        }

        // 7. Seed Warranty Registrations
        // Active Warranty for John Doe on Sale 1 (3 years from 18 months ago = 18 months remaining)
        WarrantyRegistration::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-1001'],
            [
                'customer_id' => $customer1->id,
                'product_id' => $p1001->id,
                'customer_name' => 'John Doe',
                'customer_mobile' => '+1-555-0144',
                'customer_email' => 'john.doe@example.com',
                'customer_address' => '742 Evergreen Terrace, Springfield, IL',
                'product_model' => 'FZ-PRO-500',
                'dealer_name' => 'Apex Electronics Tech',
                'dealer_address' => 'Suite 400, Silicon Galleria, Chicago, IL',
                'purchase_date' => Carbon::now()->subMonths(18)->toDateString(),
                'bill_path' => 'bills/sample_bill.png',
                'warranty_start_date' => Carbon::now()->subMonths(18)->toDateString(),
                'warranty_end_date' => Carbon::now()->subMonths(18)->addYears(3)->toDateString(),
                'status' => 'approved',
                'admin_remarks' => 'Verified and active. Includes replacements up to current active unit FZ-SN-1004.',
            ]
        );

        // Active Warranty for Jane Smith on Sale 2 (2 years from 6 months ago = 18 months remaining)
        WarrantyRegistration::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-2001'],
            [
                'customer_id' => $customer2->id,
                'product_id' => $p2001->id,
                'customer_name' => 'Jane Smith',
                'customer_mobile' => '+1-555-0288',
                'customer_email' => 'jane.smith@example.com',
                'customer_address' => '1007 Mountain View Way, Austin, TX',
                'product_model' => 'FZ-ECO-200',
                'dealer_name' => 'Metro Digital Mart',
                'dealer_address' => '420 Commercial Ave, Austin, TX',
                'purchase_date' => Carbon::now()->subMonths(6)->toDateString(),
                'bill_path' => 'bills/sample_bill.png',
                'warranty_start_date' => Carbon::now()->subMonths(6)->toDateString(),
                'warranty_end_date' => Carbon::now()->subMonths(6)->addYears(2)->toDateString(),
                'status' => 'approved',
                'admin_remarks' => 'Invoice verified against Metro Digital Mart sales record.',
            ]
        );

        // Active Warranty for Michael Brown on Sale 3
        WarrantyRegistration::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-2002'],
            [
                'customer_id' => $customer3->id,
                'product_id' => $p2002->id,
                'customer_name' => 'Michael Brown',
                'customer_mobile' => '+1-555-0377',
                'customer_email' => 'michael.brown@example.com',
                'customer_address' => '42 Ocean Drive, Miami, FL',
                'product_model' => 'FZ-ECO-200',
                'dealer_name' => 'Global Supplies Inc',
                'dealer_address' => '88 Biscayne Blvd, Miami, FL',
                'purchase_date' => Carbon::now()->subMonths(2)->toDateString(),
                'bill_path' => 'bills/sample_bill.png',
                'warranty_start_date' => Carbon::now()->subMonths(2)->toDateString(),
                'warranty_end_date' => Carbon::now()->subMonths(2)->addYears(2)->toDateString(),
                'status' => 'approved',
                'admin_remarks' => 'Approved automatically upon submission.',
            ]
        );

        // Expired Warranty for John Doe on Sale 4 (5 years warranty purchased 6 years ago = expired)
        WarrantyRegistration::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-3001'],
            [
                'customer_id' => $customer1->id,
                'product_id' => $p3001->id,
                'customer_name' => 'John Doe',
                'customer_mobile' => '+1-555-0144',
                'customer_email' => 'john.doe@example.com',
                'customer_address' => '742 Evergreen Terrace, Springfield, IL',
                'product_model' => 'FZ-MAX-900',
                'dealer_name' => 'Summit Hardware Hub',
                'dealer_address' => '12 Central Plaza, Denver, CO',
                'purchase_date' => Carbon::now()->subYears(6)->toDateString(),
                'bill_path' => 'bills/sample_bill.png',
                'warranty_start_date' => Carbon::now()->subYears(6)->toDateString(),
                'warranty_end_date' => Carbon::now()->subYears(6)->addYears(5)->toDateString(),
                'status' => 'approved',
                'admin_remarks' => 'Warranty expired on schedule.',
            ]
        );

        // Pending Registration for Jane Smith on Sale 5
        WarrantyRegistration::updateOrCreate(
            ['product_serial_number' => 'FZ-SN-3002'],
            [
                'customer_id' => $customer2->id,
                'product_id' => $p3002->id,
                'customer_name' => 'Jane Smith',
                'customer_mobile' => '+1-555-0288',
                'customer_email' => 'jane.smith@example.com',
                'customer_address' => '1007 Mountain View Way, Austin, TX',
                'product_model' => 'FZ-MAX-900',
                'dealer_name' => 'Nexus Retailers',
                'dealer_address' => '500 Tech Parkway, Dallas, TX',
                'purchase_date' => Carbon::now()->subDays(10)->toDateString(),
                'bill_path' => 'bills/sample_bill.png',
                'warranty_start_date' => Carbon::now()->subDays(10)->toDateString(),
                'warranty_end_date' => Carbon::now()->subDays(10)->addYears(5)->toDateString(),
                'status' => 'pending',
                'admin_remarks' => 'Awaiting manual invoice stamp verification.',
            ]
        );

        // 8. Seed Activity Logs
        ActivityLog::create([
            'causer_type' => Admin::class,
            'causer_id' => $admin->id,
            'action' => 'SYSTEM_INIT',
            'description' => 'System database seeded with demo catalog and initial multi-level replacement timeline.',
            'ip_address' => '127.0.0.1',
        ]);
        ActivityLog::create([
            'causer_type' => Admin::class,
            'causer_id' => $admin->id,
            'action' => 'RECORD_SALE',
            'description' => 'Sale recorded for FZ-SN-1001 to John Doe',
            'ip_address' => '127.0.0.1',
        ]);
        ActivityLog::create([
            'causer_type' => Admin::class,
            'causer_id' => $admin->id,
            'action' => 'PRODUCT_REPLACED',
            'description' => 'Replaced FZ-SN-1003 with FZ-SN-1004 for sale #1',
            'ip_address' => '127.0.0.1',
        ]);
        ActivityLog::create([
            'causer_type' => Customer::class,
            'causer_id' => $customer1->id,
            'action' => 'WARRANTY_REGISTERED',
            'description' => 'Warranty registered for serial FZ-SN-1001 by john.doe@example.com',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
