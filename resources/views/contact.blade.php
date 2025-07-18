<x-layouts.guest>
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Contact Us</h1>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @if ($settings->logo_path)
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-20">
                </div>
            @endif

            <ul class="space-y-4 text-gray-700 dark:text-gray-200">
                @if ($settings->site_name)
                    <li><strong>Site:</strong> {{ $settings->site_name }}</li>
                @endif

                @if ($settings->address)
                    <li><strong>Address:</strong> {{ $settings->address }}</li>
                @endif

                @if ($settings->phone)
                    <li>
                        <strong>Phone:</strong>
                        <a href="tel:{{ $settings->phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ $settings->phone }}
                        </a>
                    </li>
                @endif

                @if ($settings->email)
                    <li>
                        <strong>Email:</strong>
                        <a href="mailto:{{ $settings->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ $settings->email }}
                        </a>
                    </li>
                @endif

                <li class="flex space-x-4">
                    @if ($settings->facebook)
                        <a href="{{ $settings->facebook }}" target="_blank" class="text-blue-600 hover:underline">Facebook</a>
                    @endif
                    @if ($settings->twitter)
                        <a href="{{ $settings->twitter }}" target="_blank" class="text-blue-500 hover:underline">Twitter</a>
                    @endif
                    @if ($settings->instagram)
                        <a href="{{ $settings->instagram }}" target="_blank" class="text-pink-600 hover:underline">Instagram</a>
                    @endif
                    @if ($settings->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}" target="_blank" class="text-green-600 hover:underline">
                            WhatsApp
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</x-layouts.guest>
