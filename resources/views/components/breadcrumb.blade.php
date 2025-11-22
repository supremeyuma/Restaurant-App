@php
    // Get all URL segments
    $segments = request()->segments();
    $url = '';
@endphp

@if(count($segments) > 0)
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex text-gray-600">
            <!-- Home link -->
            <li><a href="{{ url('/') }}" class="text-blue-600 hover:underline">Home</a></li>

            @foreach($segments as $key => $segment)
                @php
                    $url .= '/' . $segment;
                    $isLast = $key === count($segments) - 1;
                    // Replace hyphens and underscores with spaces and capitalize words
                    $label = ucwords(str_replace(['-', '_'], ' ', $segment));
                @endphp

                <li><span class="mx-2">/</span></li>

                @if($isLast)
                    <li class="text-gray-500">{{ $label }}</li>
                @else
                    <li><a href="{{ $url }}" class="text-blue-600 hover:underline">{{ $label }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
