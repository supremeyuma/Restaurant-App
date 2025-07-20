<x-layouts.guest>
    <div class="max-w-xl mx-auto p-6" x-data="{ delivery: false }">
        <h1 class="text-xl font-bold mb-4">Checkout</h1>

        <form method="POST" action="{{ route('pay') }}">
            @csrf

            {{-- Pickup / Delivery Toggle --}}
            @if ($deliveryEnabled)
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Order Type</label>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium" :class="{ 'text-indigo-600 font-bold': !delivery }">Pickup</span>
                    <button type="button"
                        @click="delivery = !delivery"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300"
                        :class="delivery ? 'bg-indigo-600' : 'bg-gray-300'">
                        <span class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-300"
                            :class="delivery ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                    <span class="text-sm font-medium" :class="{ 'text-indigo-600 font-bold': delivery }">Delivery</span>
                </div>
            </div>
            @endif


            {{-- Name --}}
            <div class="mb-4">
                <label class="block text-sm">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" placeholder="Optional">
            </div>

            {{-- Phone --}}
            <div class="mb-4">
                <label class="block text-sm">Phone 
                    @if ($deliveryEnabled)
                        <span x-show="delivery" class="text-red-500">*</span>
                    @endif
                </label>
                <input
                    type="text"
                    name="phone"
                    class="w-full border rounded p-2"
                    :required="delivery"
                    placeholder="Phone number"
                >
            </div>

            @if ($deliveryEnabled)
            {{-- Address (Only for delivery) --}}
            <div class="mb-4" x-show="delivery" x-transition x-cloak>
                <label class="block text-sm">Delivery Address <span class="text-red-500">*</span></label>
                <textarea
                    name="address"
                    class="w-full border rounded p-2"
                    placeholder="Enter delivery address"
                    rows="3"
                    :required="delivery"
                ></textarea>
            </div>
            @endif

            {{-- Order Summary --}}
            <h2 class="font-semibold mt-6 mb-2">Order Summary</h2>
            <ul class="mb-4">
                @foreach ($cart as $item)
                    <li class="flex justify-between text-sm">
                        <span>{{ $item['name'] }} x{{ $item['qty'] }}</span>
                        <span>₦{{ number_format($item['qty'] * $item['price']) }}</span>
                    </li>
                @endforeach
            </ul>

            {{-- Delivery Fee Display --}}
            <div x-show="delivery" x-transition class="text-right text-sm text-gray-700 mb-2" x-cloak>
                Delivery Fee: ₦{{ number_format($deliveryFee) }}
            </div>


            <div class="font-bold text-right mb-4">
                Total: 
                <span x-text="delivery
                    ? '{{ number_format(collect($cart)->sum(fn($i) => $i['qty'] * $i['price']) + $deliveryFee) }}'
                    : '{{ number_format(collect($cart)->sum(fn($i) => $i['qty'] * $i['price'])) }}'"></span>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">
                Pay with Paystack
            </button>
        </form>
    </div>
</x-layouts.guest>
