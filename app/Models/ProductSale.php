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
        'assigned_to',
        'dealer_name',
        'dealer_address',
        'customer_name',
        'customer_mobile',
        'customer_address',
        'invoice_number',
    ];

    public function isAssignedToDealer(): bool
    {
        return !empty($this->dealer_name);
    }

    public function isAssignedToCustomer(): bool
    {
        return !empty($this->customer_name);
    }

    public function getAssignmentBadgeAttribute(): array
    {
        $hasDealer = !empty($this->dealer_name);
        $hasCustomer = !empty($this->customer_name);

        if ($hasDealer && $hasCustomer) {
            return [
                'type' => 'both',
                'label' => 'Dealer & Customer',
                'class' => 'bg-info-subtle text-info border border-info border-opacity-25',
                'icon' => 'fas fa-users',
            ];
        } elseif ($hasDealer) {
            return [
                'type' => 'dealer',
                'label' => 'Dealer Only',
                'class' => 'bg-primary-subtle text-primary border border-primary border-opacity-25',
                'icon' => 'fas fa-store',
            ];
        } elseif ($hasCustomer) {
            return [
                'type' => 'customer',
                'label' => 'Customer Only',
                'class' => 'bg-success-subtle text-success border border-success border-opacity-25',
                'icon' => 'fas fa-user',
            ];
        }

        return [
            'type' => 'none',
            'label' => 'Unassigned',
            'class' => 'bg-secondary-subtle text-secondary border',
            'icon' => 'fas fa-question-circle',
        ];
    }

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
