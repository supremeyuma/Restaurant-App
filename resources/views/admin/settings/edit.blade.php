<x-layouts.admin>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Site Settings</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-4" x-data="{ deliveryEnabled: {{ old('delivery_enabled', $setting->delivery_enabled) ? 'true' : 'false' }} }">
            @csrf

            <x-input-label value="Site Name" />
            <x-text-input name="site_name" value="{{ old('site_name', $setting->site_name) }}" class="w-full" />

            <x-input-label value="Logo" />
            @if ($setting->logo_path)
                <img src="{{ Storage::url($setting->logo_path) }}" class="h-16 mb-2" alt="Logo">
            @endif
            <input type="file" name="logo" class="block w-full border rounded" />

            <x-input-label value="Banner" />
            @if ($setting->banner_path)
                <img src="{{ Storage::url($setting->banner_path) }}" class="h-16 mb-2" alt="Banner">
            @endif
            <input type="file" name="banner" class="block w-full border rounded" />

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

            {{-- Delivery Toggle --}}
            <div class="mt-6">
                <label class="block text-sm font-medium mb-1">Enable Deliveries</label>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium" :class="{ 'text-gray-400': !deliveryEnabled, 'text-indigo-600 font-bold': deliveryEnabled }">Off</span>
                    <button type="button"
                        @click="deliveryEnabled = !deliveryEnabled"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300"
                        :class="deliveryEnabled ? 'bg-indigo-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-300"
                            :class="deliveryEnabled ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                    <span class="text-sm font-medium" :class="{ 'text-gray-400': !deliveryEnabled, 'text-indigo-600 font-bold': deliveryEnabled }">On</span>
                </div>
                <input type="hidden" name="delivery_enabled" :value="deliveryEnabled ? 1 : 0">
            </div>

            {{-- Delivery Fee Input --}}
            <div x-show="deliveryEnabled" x-transition>
                <x-input-label value="Delivery Fee (₦)" class="mt-4" />
                <x-text-input type="number" step="0.01" name="delivery_fee" value="{{ old('delivery_fee', $setting->delivery_fee) }}" class="w-full" />
            </div>

            <x-primary-button>Save Settings</x-primary-button>
        </form>
    </div>
</x-layouts.admin>
