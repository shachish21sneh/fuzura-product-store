<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Retrieve a dynamic system setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}
