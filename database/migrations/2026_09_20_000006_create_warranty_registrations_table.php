<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_mobile')->index();
            $table->string('customer_email');
            $table->text('customer_address')->nullable();
            $table->string('product_model');
            $table->string('product_serial_number')->unique()->index();
            $table->string('dealer_name');
            $table->text('dealer_address')->nullable();
            $table->date('purchase_date');
            $table->string('bill_path')->nullable();
            $table->date('warranty_start_date');
            $table->date('warranty_end_date')->index();
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved')->index();
            $table->text('admin_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_registrations');
    }
};
