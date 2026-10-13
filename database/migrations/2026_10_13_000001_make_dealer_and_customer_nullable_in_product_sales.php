<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            $table->string('dealer_name')->nullable()->change();
            $table->string('customer_name')->nullable()->change();
            $table->string('customer_mobile')->nullable()->change();
            if (!Schema::hasColumn('product_sales', 'assigned_to')) {
                $table->string('assigned_to', 20)->default('both')->after('bill_date');
            }
        });

        // Set existing records' assigned_to correctly based on current data
        if (Schema::hasColumn('product_sales', 'assigned_to')) {
            DB::table('product_sales')
                ->whereNotNull('dealer_name')
                ->where('dealer_name', '!=', '')
                ->whereNotNull('customer_name')
                ->where('customer_name', '!=', '')
                ->update(['assigned_to' => 'both']);

            DB::table('product_sales')
                ->whereNotNull('dealer_name')
                ->where('dealer_name', '!=', '')
                ->where(function ($q) {
                    $q->whereNull('customer_name')->orWhere('customer_name', '');
                })
                ->update(['assigned_to' => 'dealer']);

            DB::table('product_sales')
                ->whereNotNull('customer_name')
                ->where('customer_name', '!=', '')
                ->where(function ($q) {
                    $q->whereNull('dealer_name')->orWhere('dealer_name', '');
                })
                ->update(['assigned_to' => 'customer']);
        }
    }

    public function down(): void
    {
        Schema::table('product_sales', function (Blueprint $table) {
            if (Schema::hasColumn('product_sales', 'assigned_to')) {
                $table->dropColumn('assigned_to');
            }
        });
    }
};
