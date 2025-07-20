<x-layouts.admin>
    <div class="max-w-xl mx-auto py-6">
        <h2 class="text-xl font-semibold mb-4">Create Category</h2>

        <form method="POST"  action="{{ route('admin.categories.update', $category->id) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" class="w-full border-gray-300 rounded shadow-sm" value="{{ old('name', $category->name) }}" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Position (optional)</label>
                <input type="number" name="position" class="w-full border-gray-300 rounded shadow-sm">
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">Save</button>
        </form>
    </div>
</x-layouts.admin>
