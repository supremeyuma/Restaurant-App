<x-layouts.admin>
    <div class="max-w-full mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">All Items</h1>
            <a href="{{ route('admin.items.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">+ New</a>
        </div>

        @if(session('success'))
            <div class="mb-3 text-green-700 bg-green-100 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Image</th>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2 text-left">Category</th>
                        <th class="px-3 py-2 text-left">Price</th>
                        <th class="px-3 py-2 text-left">Wait&nbsp;Time</th>
                        <th class="px-3 py-2 text-left">Available</th>
                        <th class="px-3 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/'.$item->image_path) }}"
                                         class="w-12 h-12 object-cover rounded">
                                @endif
                            </td>
                            <td class="px-3 py-2">{{ $item->name }}</td>
                            <td class="px-3 py-2">{{ $item->category->name }}</td>
                            <td class="px-3 py-2">₦{{ number_format($item->price,2) }}</td>
                            <td class="px-3 py-2">{{ $item->wait_time_minutes }} min</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 text-xs rounded
                                    {{ $item->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $item->is_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-right space-x-2">
                                <a href="{{ route('admin.items.edit', $item) }}"
                                   class="text-indigo-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.items.destroy', $item) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Delete this item?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center p-4 text-gray-500">No items yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
