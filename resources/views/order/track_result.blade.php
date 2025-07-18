<x-layouts.guest>
    <div class="max-w-lg mx-auto mt-12 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Order Status</h2>

        <p class="mb-2"><strong>Name:</strong> {{ $order->name }}</p>
        <p class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</p>
        <p class="mb-2"><strong>Status:</strong> 
            <span class="inline-block px-2 py-1 text-sm bg-gray-100 rounded">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <h3 class="mt-6 font-semibold">Items:</h3>
        <ul class="list-disc pl-6 text-sm">
            @foreach ($order->items as $item)
                <li>{{ $item->name }} x {{ $item->pivot->quantity }}</li>
            @endforeach
        </ul>
    </div>
</x-layouts.guest>
