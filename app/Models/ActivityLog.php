<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'causer_type',
        'causer_id',
        'action',
        'description',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer()
    {
        return $this->morphTo();
    }

    public static function record(string $action, string $description, ?array $properties = null, $causer = null): self
    {
        if (!$causer) {
            if (Auth::guard('admin')->check()) {
                $causer = Auth::guard('admin')->user();
            } elseif (Auth::guard('customer')->check()) {
                $causer = Auth::guard('customer')->user();
            }
        }

        return self::create([
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer ? $causer->id : null,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => Request::ip(),
        ]);
    }
}
