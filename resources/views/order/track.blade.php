<x-layouts.guest>
    <div class="max-w-lg mx-auto mt-12 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Track Your Order</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('track.check') }}">
            @csrf
            <label class="block mb-2 text-sm font-medium">Enter Pickup Code</label>
            <input type="text" name="code" class="w-full px-4 py-2 border rounded" placeholder="e.g. ABC1234" required>
            <button class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded">Track Order</button>
        </form>
    </div>
</x-layouts.guest>
