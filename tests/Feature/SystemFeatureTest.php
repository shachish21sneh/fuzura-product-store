<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\WarrantyRegistration;
use App\Services\ReplacementTimelineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SystemFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('FUZURA');
        $response->assertSee('Check Warranty');
    }

    public function test_public_serial_search_finds_timeline()
    {
        $response = $this->get('/search?serial_number=FZ-SN-1002');
        $response->assertStatus(200);
        $response->assertSee('FZ-SN-1002');
        $response->assertSee('FZ-SN-1001'); // Root
        $response->assertSee('FZ-SN-1004'); // Current active
        $response->assertSee('WARRANTY ACTIVE');
    }

    public function test_public_serial_search_not_found_shows_empty_state()
    {
        $response = $this->get('/search?serial_number=UNKNOWN-SERIAL-999');
        $response->assertStatus(200);
        $response->assertSee('No Product Found');
    }

    public function test_admin_can_view_dashboard_and_modules()
    {
        $admin = Admin::where('email', 'admin@fuzura.com')->first();
        $this->assertNotNull($admin);

        // Dashboard
        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('System Overview & Analytics');

        // Products
        $response = $this->actingAs($admin, 'admin')->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Product Master Catalog');

        // Sales
        $response = $this->actingAs($admin, 'admin')->get('/admin/sales');
        $response->assertStatus(200);
        $response->assertSee('Product Sales Registry');

        // Replacements
        $response = $this->actingAs($admin, 'admin')->get('/admin/replacements');
        $response->assertStatus(200);
        $response->assertSee('Replacement Audit');

        // Warranties
        $response = $this->actingAs($admin, 'admin')->get('/admin/warranties');
        $response->assertStatus(200);
        $response->assertSee('Customer Warranty Registrations');

        // Customers
        $response = $this->actingAs($admin, 'admin')->get('/admin/customers');
        $response->assertStatus(200);
        $response->assertSee('Customer Directory');

        // Reports
        $response = $this->actingAs($admin, 'admin')->get('/admin/reports');
        $response->assertStatus(200);
        $response->assertSee('System Reports');
    }

    public function test_admin_can_create_product()
    {
        $admin = Admin::where('email', 'admin@fuzura.com')->first();

        $serial = 'TEST-PROD-' . rand(1000, 9999);
        $response = $this->actingAs($admin, 'admin')->postJson('/admin/products', [
            'model_number' => 'FZ-TEST-100',
            'serial_number' => $serial,
            'manufacturing_date' => Carbon::now()->toDateString(),
            'warranty_period_years' => 2,
            'spare_details' => 'Test spare parts',
            'spare_vendor' => 'Test Vendor',
            'status' => 'available',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('products', ['serial_number' => $serial]);
    }

    public function test_customer_can_view_dashboard_and_products()
    {
        $customer = Customer::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($customer);

        // Customer Dashboard
        $response = $this->actingAs($customer, 'customer')->get('/customer/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Welcome, John Doe!');

        // My Products
        $response = $this->actingAs($customer, 'customer')->get('/customer/products');
        $response->assertStatus(200);
        $response->assertSee('My Registered Products');

        // Register Product form
        $response = $this->actingAs($customer, 'customer')->get('/customer/products/register');
        $response->assertStatus(200);
        $response->assertSee('Hardware Registration Form');
    }

    public function test_customer_warranty_certificate_renders()
    {
        $customer = Customer::where('email', 'john.doe@example.com')->first();
        $warranty = WarrantyRegistration::where('customer_id', $customer->id)->first();
        $this->assertNotNull($warranty);

        $response = $this->actingAs($customer, 'customer')->get("/customer/products/{$warranty->id}/certificate");
        $response->assertStatus(200);
        $response->assertSee('Official Certificate of Limited Hardware Warranty');
        $response->assertSee($warranty->product_serial_number);
    }
}
