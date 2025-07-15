<x-app-layout>
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">All Categories</h1>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">+ New</a>
        </div>

        @if(session('success'))
            <div class="mb-3 text-green-700 bg-green-100 p-3 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Position</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $category->name }}</td>
                            <td class="px-4 py-2">{{ $category->position }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-4 text-gray-500">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
