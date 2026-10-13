<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
    ];

    /**
     * Cache all settings in memory for the request lifecycle.
     */
    protected static ?array $settingsCache = null;

    /**
     * Retrieve a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (self::$settingsCache === null) {
                self::$settingsCache = Cache::remember('app_settings_map', 3600, function () {
                    return self::pluck('value', 'key')->toArray();
                });
            }

            if (array_key_exists($key, self::$settingsCache)) {
                $val = self::$settingsCache[$key];
                return ($val !== null && $val !== '') ? $val : $default;
            }
        } catch (\Throwable $e) {
            // DB might not be ready during early migration or CLI setup
        }

        return $default;
    }

    /**
     * Set or update a setting value.
     */
    public static function set(string $key, $value, ?string $group = 'general', ?string $label = null, ?string $type = 'text'): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group ?? 'general',
                'label' => $label,
                'type' => $type ?? 'text',
            ]
        );

        self::clearCache();

        return $setting;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        self::$settingsCache = null;
        Cache::forget('app_settings_map');
    }
}
