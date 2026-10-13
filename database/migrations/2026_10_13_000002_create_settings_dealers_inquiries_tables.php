<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings Table
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique()->index();
                $table->text('value')->nullable();
                $table->string('group')->default('general')->index();
                $table->string('label')->nullable();
                $table->string('type')->default('text'); // text, textarea, number, boolean
                $table->timestamps();
            });
        }

        // 2. Dealers Table
        if (!Schema::hasTable('dealers')) {
            Schema::create('dealers', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->unique()->nullable();
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        // 3. Contact Inquiries Table
        if (!Schema::hasTable('contact_inquiries')) {
            Schema::create('contact_inquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject');
                $table->text('message');
                $table->string('status', 20)->default('unread')->index(); // unread, in_progress, replied, closed
                $table->text('admin_notes')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->timestamps();
            });
        }

        // Seed initial default settings
        $defaultSettings = [
            ['key' => 'site_name', 'value' => 'Fuzura Store', 'group' => 'general', 'label' => 'Site / Brand Name', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'Enterprise Product Registration & Warranty Management System', 'group' => 'general', 'label' => 'Platform Tagline', 'type' => 'text'],
            ['key' => 'support_phone', 'value' => '1-800-FUZURA-CARE', 'group' => 'contact', 'label' => 'Support Toll-Free Phone', 'type' => 'text'],
            ['key' => 'support_email', 'value' => 'warranty@fuzura.in', 'group' => 'contact', 'label' => 'Official Support Email', 'type' => 'text'],
            ['key' => 'support_hours', 'value' => 'Monday to Saturday, 9:00 AM – 7:00 PM IST', 'group' => 'contact', 'label' => 'Helpdesk Operating Hours', 'type' => 'text'],
            ['key' => 'office_address', 'value' => 'Fuzura Technology Park, Industrial Hub, New Delhi, 110020, India', 'group' => 'contact', 'label' => 'Head Office Address', 'type' => 'textarea'],
            ['key' => 'default_warranty_years', 'value' => '2', 'group' => 'warranty', 'label' => 'Default Hardware Warranty Period (Years)', 'type' => 'number'],
            ['key' => 'hero_title', 'value' => 'Protect & Trace Hardware with Zero Friction', 'group' => 'homepage', 'label' => 'Homepage Hero Headline', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'value' => 'Enterprise serial verification, transparent multi-level replacement tracking, and automated digital certificate issuance powered by Fuzura\'s immutable ledger.', 'group' => 'homepage', 'label' => 'Homepage Hero Subtitle', 'type' => 'textarea'],
            ['key' => 'demo_serials', 'value' => '', 'group' => 'homepage', 'label' => 'Custom Demo Serials (Leave blank for auto-detected active serials)', 'type' => 'text'],
        ];

        foreach ($defaultSettings as $s) {
            DB::table('settings')->updateOrInsert(['key' => $s['key']], array_merge($s, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Seed initial dealers from current database sales and existing catalog
        $initialDealers = [
            ['name' => 'krishna electronics', 'code' => 'DLR-KE-001', 'contact_person' => 'Store Manager', 'phone' => '+91 98765 43210', 'email' => 'sales@krishnaelectronics.com', 'city' => 'New Delhi', 'address' => 'Shop 14, Main Electronics Market, New Delhi'],
            ['name' => 'Apex Electronics Tech', 'code' => 'DLR-AEX-002', 'contact_person' => 'Robert Miller', 'phone' => '+1 312 555 0144', 'email' => 'contact@apexelectronics.com', 'city' => 'Chicago', 'address' => 'Suite 400, Silicon Galleria, Chicago, IL'],
            ['name' => 'Metro Digital Mart', 'code' => 'DLR-MDM-003', 'contact_person' => 'Sarah Jenkins', 'phone' => '+1 512 555 0288', 'email' => 'austin@metrodigital.com', 'city' => 'Austin', 'address' => '420 Commercial Ave, Austin, TX'],
            ['name' => 'Global Supplies Inc', 'code' => 'DLR-GSI-004', 'contact_person' => 'Carlos Rodriguez', 'phone' => '+1 305 555 0377', 'email' => 'info@globalsupplies.com', 'city' => 'Miami', 'address' => '88 Biscayne Blvd, Miami, FL'],
            ['name' => 'Summit Hardware Hub', 'code' => 'DLR-SHH-005', 'contact_person' => 'David Wilson', 'phone' => '+1 303 555 0499', 'email' => 'denver@summithardware.com', 'city' => 'Denver', 'address' => '12 Central Plaza, Denver, CO'],
            ['name' => 'Nexus Retailers', 'code' => 'DLR-NXR-006', 'contact_person' => 'Priya Patel', 'phone' => '+1 214 555 0588', 'email' => 'dallas@nexusretail.com', 'city' => 'Dallas', 'address' => '500 Tech Parkway, Dallas, TX'],
        ];

        foreach ($initialDealers as $d) {
            DB::table('dealers')->updateOrInsert(['name' => $d['name']], array_merge($d, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
        Schema::dropIfExists('dealers');
        Schema::dropIfExists('settings');
    }
};
