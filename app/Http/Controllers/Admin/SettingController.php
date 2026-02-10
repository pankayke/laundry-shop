<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::instance();

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'shop_name'    => ['required', 'string', 'max:255'],
            'shop_address' => ['required', 'string', 'max:500'],
            'shop_phone'   => ['required', 'string', 'max:20'],
            'wash_price'   => ['required', 'numeric', 'min:0'],
            'dry_price'    => ['required', 'numeric', 'min:0'],
            'fold_price'   => ['required', 'numeric', 'min:0'],
        ]);

        $settings = Setting::instance();
        $settings->update($validated);
        Setting::clearCache();

        return back()->with('success', 'Settings updated successfully.');
    }
}
