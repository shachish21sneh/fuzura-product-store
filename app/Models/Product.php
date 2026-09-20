<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_number',
        'serial_number',
        'manufacturing_date',
        'warranty_period_years',
        'spare_details',
        'spare_vendor',
        'status',
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'warranty_period_years' => 'integer',
    ];

    public function sale()
    {
        return $this->hasOne(ProductSale::class, 'product_id');
    }

    public function warrantyRegistrations()
    {
        return $this->hasMany(WarrantyRegistration::class, 'product_id');
    }

    public function outgoingReplacements()
    {
        return $this->hasMany(ProductReplacement::class, 'old_product_id');
    }

    public function incomingReplacements()
    {
        return $this->hasMany(ProductReplacement::class, 'new_product_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function scopeReplaced($query)
    {
        return $query->where('status', 'replaced');
    }
}
