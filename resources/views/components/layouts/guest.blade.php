@php
use Illuminate\Support\Str;

// Fetch the logo path from your settings table
$appLogoPath = App\Models\Setting::first()->logo_path ?? null;

// Fetch the banner image path (fallback to default)
$bannerImagePath = App\Models\Setting::first()->banner_path ?? 'default-banner.jpg';

// Generate breadcrumb segments
$segments = request()->segments();
@endphp

<x-layouts.app>
<div class="min-h-screen flex flex-col bg-gray-100 relative">

    {{-- Top Navigation Bar --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-white bg-opacity-80 backdrop-blur-md shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
            {{-- Logo --}}
            <a href="/">
                <x-application-logo class="w-10 h-10" :logoPath="$appLogoPath"/>
            </a>

            {{-- Desktop Links --}}
            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">Home</a>
                <a href="/menu" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">Menu</a>
                <a href="/about-us" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">About Us</a>
            </nav>

            {{-- Sidebar Button --}}
            <button id="sidebarToggle" class="text-gray-700 md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </header>

    @php
        // Fetch contact info from the settings table
        $settings = App\Models\Setting::first();
        $contactPhone = $settings->phone ?? 'Not Available';
        $contactEmail = $settings->email ?? 'Not Available';
        $contactAddress = $settings->address ?? 'Not Available';
        
        // Generate Google Maps link
        $mapsLink = $contactAddress !== 'Not Available' ? 'https://www.google.com/maps/search/' . urlencode($contactAddress) : '#';

        // Encode address for URLs
        $encodedAddress = urlencode($contactAddress);

        // Google Maps embed link
        $mapsEmbed = $contactAddress !== 'Not Available' ? 'https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=' . $encodedAddress : '';
    @endphp

    {{-- Sidebar + Backdrop --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden transition-opacity duration-300"></div>

    <div id="sidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        
        <div class="relative flex flex-col h-full p-6 sm:p-8">
            
            {{-- Modern Close Button (Top Right) --}}
            <button id="sidebarClose" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition-colors duration-200 focus:outline-none p-1 rounded-full bg-gray-50 hover:bg-red-50">
                {{-- X Icon (Tailwind Heroicons equivalent) --}}
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            {{-- Content Section (Starts below the button's position) --}}
            <div class="pt-10">
                <h2 class="text-2xl font-extrabold text-gray-900 border-b pb-2 mb-4">Contact & Information</h2>
                
                {{-- Phone --}}
                <div class="mb-4">
                    <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wider">Phone</h3>
                    <p class="text-lg text-gray-800"><a href="tel:{{ $contactPhone }}" class="text-blue-600 hover:underline">{{ $contactPhone }}</a></p>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wider">Email</h3>
                    <p class="text-lg text-gray-800"><a href="mailto:{{ $contactEmail }}" class="text-blue-600 hover:underline">{{ $contactEmail }}</a></p>
                </div>

                {{-- Address --}}
                <div class="mb-4">
                    <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wider">Address</h3>
                    <p class="text-lg text-gray-800">
                        {{ $contactAddress }}
                        @if($contactAddress !== 'Not Available')
                            <a href="{{ $mapsLink }}" target="_blank" class="block mt-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                View on Google Maps
                            </a>
                        @endif
                    </p>

                     {{-- Embedded Google Map --}}
                    <!--@if($contactAddress !== 'Not Available')
                        <iframe
                            width="100%"
                            height="200"
                            class="rounded shadow-sm"
                            frameborder="0"
                            style="border:0"
                            src="{{ $mapsEmbed }}"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    @endif-->
                </div>

            </div>

        </div>
    </div>


    {{-- Banner Section --}}
    <div class="relative w-full h-64 mt-16">
        {{-- Banner Image with reduced opacity --}}
        <img src="{{ asset('storage/' . $bannerImagePath) }}" alt="Banner" class="w-full h-full object-cover opacity-60">

        {{-- Centered Logo --}}
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
            <a href="/">
                <x-application-logo class="w-24 h-24 border-4 border-white rounded-full shadow-lg hover:scale-105 transition-transform duration-300" :logoPath="$appLogoPath"/>
            </a>
        </div>

        {{-- Automatic Breadcrumb --}}
        {{-- Automatic Breadcrumb --}}
        <div id="breadcrumb" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-20 bg-white bg-opacity-80 px-4 py-2 rounded shadow-sm opacity-0 translate-y-4 transition-all duration-700">
            <nav class="text-gray-700 text-sm" aria-label="Breadcrumb">
                <ol class="list-reset flex flex-wrap justify-center">
                    <li>
                        <a href="/" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-300">Home</a>
                    </li>
                    @foreach($segments as $index => $segment)
                        <li class="flex items-center">
                            <span class="mx-2">/</span>
                            @if($index + 1 < count($segments))
                                <a href="{{ url(implode('/', array_slice($segments, 0, $index + 1))) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-300">
                                    {{ Str::title(str_replace('-', ' ', $segment)) }}
                                </a>
                            @else
                                <span class="text-gray-500">{{ Str::title(str_replace('-', ' ', $segment)) }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

    </div>

    {{-- Main Content --}}
    <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>

    {{-- Sidebar Toggle Script --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const openBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        const openSidebar = () => {
            sidebar.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('translate-x-full');
            backdrop.classList.add('hidden');
        };

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);
    </script>

    <script>
    // Fade in breadcrumb after page load
    document.addEventListener('DOMContentLoaded', () => {
        const breadcrumb = document.getElementById('breadcrumb');
        // Trigger fade-in animation
        setTimeout(() => {
            breadcrumb.classList.remove('opacity-0', 'translate-y-4');
            breadcrumb.classList.add('opacity-100', 'translate-y-0');
        }, 200); // slight delay for nicer effect
    });

    // Optional: fade in when scrolling (if banner is tall)
    window.addEventListener('scroll', () => {
        const breadcrumb = document.getElementById('breadcrumb');
        if (window.scrollY > 50) {
            breadcrumb.classList.add('opacity-100', 'translate-y-0');
        }
    });
</script>

</div>
</x-layouts.app>
