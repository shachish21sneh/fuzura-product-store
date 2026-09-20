<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_model',
        'product_serial_number',
        'bill_date',
        'dealer_name',
        'dealer_address',
        'customer_name',
        'customer_mobile',
        'customer_address',
        'invoice_number',
    ];

    protected $casts = [
        'bill_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function replacements()
    {
        return $this->hasMany(ProductReplacement::class, 'sale_id')->orderBy('replacement_date', 'asc');
    }

    public function warrantyRegistration()
    {
        return $this->hasOne(WarrantyRegistration::class, 'product_serial_number', 'product_serial_number');
    }
}
