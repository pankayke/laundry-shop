<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'shop_name'            => ['required', 'string', 'max:255'],
            'shop_address'         => ['required', 'string', 'max:500'],
            'shop_phone'           => ['required', 'string', 'max:20'],
            'wash_price'           => ['required', 'numeric', 'min:0'],
            'dry_price'            => ['required', 'numeric', 'min:0'],
            'fold_price'           => ['required', 'numeric', 'min:0'],
            'gcash_number'         => ['required', 'string', 'max:20'],
            'payment_instructions' => ['nullable', 'string', 'max:500'],
            'qr_code'              => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $settings = Setting::instance();

        // Handle QR code upload
        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            if ($settings->qr_code_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $settings->qr_code_path));
            }
            $path = $request->file('qr_code')->store('images', 'public');
            $validated['qr_code_path'] = '/storage/' . $path;
        }

        unset($validated['qr_code']);
        $settings->update($validated);
        Setting::clearCache();

        return back()->with('success', 'Settings updated successfully.');
    }
}
