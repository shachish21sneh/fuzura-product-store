<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_model');
            $table->string('product_serial_number')->unique()->index();
            $table->date('bill_date');
            $table->string('dealer_name');
            $table->text('dealer_address')->nullable();
            $table->string('customer_name');
            $table->string('customer_mobile')->index();
            $table->text('customer_address')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sales');
    }
};
