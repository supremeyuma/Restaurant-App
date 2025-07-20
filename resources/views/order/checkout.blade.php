<x-guest-layout>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-xl font-bold mb-4">Checkout</h1>

        <form method="POST" action="{{ route('pay') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" placeholder="Optional">
            </div>
            <div class="mb-4">
                <label class="block text-sm">Phone</label>
                <input type="text" name="phone" class="w-full border rounded p-2" placeholder="Optional">
            </div>

            <h2 class="font-semibold mt-6 mb-2">Order Summary</h2>
            <ul class="mb-4">
                @foreach ($cart as $item)
                    <li class="flex justify-between text-sm">
                        <span>{{ $item['name'] }} x{{ $item['qty'] }}</span>
                        <span>₦{{ number_format($item['qty'] * $item['price']) }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="font-bold text-right mb-4">
                Total: ₦{{ number_format(collect($cart)->sum(fn($i) => $i['qty'] * $i['price'])) }}
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Pay with Paystack
            </button>
        </form>
    </div>
</x-guest-layout>
