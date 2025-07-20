<x-layouts.admin>
    <div class="max-w-3xl mx-auto bg-white  p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Site Settings</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <x-input-label value="Site Name" />
            <x-text-input name="site_name" value="{{ old('site_name', $setting->site_name) }}" class="w-full" />

            <x-input-label value="Logo" />
            @if ($setting->logo_path)
                <img src="{{ Storage::url($setting->logo_path) }}" class="h-16 mb-2" alt="Logo">
            @endif
            <input type="file" name="logo" class="block w-full border rounded" />

            <x-input-label value="Contact Email" />
            <x-text-input name="email" value="{{ old('email', $setting->email) }}" class="w-full" />

            <x-input-label value="Phone" />
            <x-text-input name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full" />

            <x-input-label value="Address" />
            <textarea name="address" rows="3" class="w-full rounded border">{{ old('address', $setting->address) }}</textarea>

            <x-input-label value="Facebook URL" />
            <x-text-input name="facebook" value="{{ old('facebook', $setting->facebook) }}" class="w-full" />

            <x-input-label value="Twitter URL" />
            <x-text-input name="twitter" value="{{ old('twitter', $setting->twitter) }}" class="w-full" />

            <x-input-label value="Instagram URL" />
            <x-text-input name="instagram" value="{{ old('instagram', $setting->instagram) }}" class="w-full" />

            <x-input-label value="WhatsApp Number or Link" />
            <x-text-input name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" class="w-full" />

            <x-primary-button>Save Settings</x-primary-button>
        </form>
    </div>
</x-layouts.admin>
