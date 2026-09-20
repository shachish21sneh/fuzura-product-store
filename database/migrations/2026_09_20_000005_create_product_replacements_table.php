<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('product_sales')->onDelete('cascade');
            $table->foreignId('old_product_id')->constrained('products')->onDelete('cascade');
            $table->string('old_serial_number')->index();
            $table->foreignId('new_product_id')->constrained('products')->onDelete('cascade');
            $table->string('new_serial_number')->index();
            $table->string('dealer_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('replacement_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_replacements');
    }
};
