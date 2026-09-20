<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('model_number')->index();
            $table->string('serial_number')->unique()->index();
            $table->date('manufacturing_date');
            $table->unsignedTinyInteger('warranty_period_years')->default(1);
            $table->text('spare_details')->nullable();
            $table->string('spare_vendor')->nullable();
            $table->enum('status', ['available', 'sold', 'replaced', 'scrapped'])->default('available')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
