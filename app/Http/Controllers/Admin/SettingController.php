<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::firstOrCreate([]);
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:92048',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::delete($setting->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        };

        if ($request->hasFile('banner')) {
            if ($setting->banner_path) {
                Storage::delete($setting->banner_path);
            }
            $validated['banner_path'] = $request->file('banner')->store('banners', 'public');
        };
        
        $setting->delivery_enabled = $request->boolean('delivery_enabled');
        $setting->delivery_fee = $request->delivery_fee ?? 0;


        $setting->update($validated);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
