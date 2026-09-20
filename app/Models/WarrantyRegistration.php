<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'customer_address',
        'product_model',
        'product_serial_number',
        'dealer_name',
        'dealer_address',
        'purchase_date',
        'bill_path',
        'warranty_start_date',
        'warranty_end_date',
        'status',
        'admin_remarks',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(ProductSale::class, 'product_serial_number', 'product_serial_number');
    }

    public function isExpired(): bool
    {
        return Carbon::now()->startOfDay()->gt($this->warranty_end_date->startOfDay());
    }

    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int) Carbon::now()->startOfDay()->diffInDays($this->warranty_end_date->startOfDay());
    }
}
