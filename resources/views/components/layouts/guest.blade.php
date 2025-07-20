@php
    // Fetch the logo path from your settings table
    // Adjust this line based on how your settings are structured.
    // For example, if you have a single row with 'logo_path' column:
    $appLogoPath = App\Models\Setting::first()->logo_path ?? null;

    // Or if you have a key-value pair setting like 'app_logo_path':
    // $appLogoPath = App\Models\Setting::where('key', 'app_logo_path')->first()->value ?? null;

    // Make sure the path stored in the database is relative to storage/app/public
    // e.g., 'logos/my_awesome_logo.png'
@endphp

<x-layouts.app>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    
    

    <div>
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" :logoPath="$appLogoPath"/>
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
</x-layouts.app>