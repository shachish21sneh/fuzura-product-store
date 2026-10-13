<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'unread' => [
                'label' => 'New / Unread',
                'class' => 'bg-danger-subtle text-danger border border-danger border-opacity-25',
                'icon' => 'fas fa-envelope',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'class' => 'bg-warning-subtle text-warning border border-warning border-opacity-25',
                'icon' => 'fas fa-spinner fa-spin',
            ],
            'replied' => [
                'label' => 'Replied',
                'class' => 'bg-info-subtle text-info border border-info border-opacity-25',
                'icon' => 'fas fa-reply',
            ],
            'closed' => [
                'label' => 'Resolved / Closed',
                'class' => 'bg-success-subtle text-success border border-success border-opacity-25',
                'icon' => 'fas fa-check-circle',
            ],
            default => [
                'label' => ucfirst($this->status),
                'class' => 'bg-secondary-subtle text-secondary',
                'icon' => 'fas fa-info-circle',
            ],
        };
    }
}
