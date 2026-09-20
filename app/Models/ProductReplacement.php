<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReplacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'old_product_id',
        'old_serial_number',
        'new_product_id',
        'new_serial_number',
        'dealer_name',
        'customer_name',
        'replacement_date',
        'remarks',
    ];

    protected $casts = [
        'replacement_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(ProductSale::class, 'sale_id');
    }

    public function oldProduct()
    {
        return $this->belongsTo(Product::class, 'old_product_id');
    }

    public function newProduct()
    {
        return $this->belongsTo(Product::class, 'new_product_id');
    }
}
