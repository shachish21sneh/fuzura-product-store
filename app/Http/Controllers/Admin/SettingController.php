<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('id')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputSettings = $request->except(['_token', '_method']);

        foreach ($inputSettings as $key => $value) {
            $existing = Setting::where('key', $key)->first();
            if ($existing) {
                $existing->update(['value' => $value]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => 'general',
                    'label' => ucwords(str_replace('_', ' ', $key)),
                ]);
            }
        }

        Setting::clearCache();

        ActivityLog::record(
            'SETTINGS_UPDATED',
            'System settings and platform configurations updated by administrator.',
            'settings'
        );

        return redirect()->route('admin.settings.index')->with('success', 'System settings updated successfully and dynamic cache refreshed.');
    }
}
