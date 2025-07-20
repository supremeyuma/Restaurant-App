@props(['item' => null, 'categories'])
@csrf
@if($item) @method('PUT') @endif

<div class="space-y-4">
    <div>
        <label class="block mb-1 font-medium">Category</label>
        <select name="category_id" class="w-full border-gray-300 rounded"
                required>
            @foreach($categories as $id => $name)
                <option value="{{ $id }}"
                  @selected(old('category_id', $item->category_id ?? '') == $id)>
                  {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">Name</label>
        <input type="text" name="name" class="w-full border-gray-300 rounded"
               value="{{ old('name', $item->name ?? '') }}" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">Price (₦)</label>
        <input type="number" step="0.01" name="price"
               class="w-full border-gray-300 rounded"
               value="{{ old('price', $item->price ?? '') }}" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">Wait Time (minutes)</label>
        <input type="number" name="wait_time_minutes"
               class="w-full border-gray-300 rounded"
               value="{{ old('wait_time_minutes', $item->wait_time_minutes ?? 15) }}" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="3"
                  class="w-full border-gray-300 rounded">{{ old('description', $item->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block mb-1 font-medium">Image</label>
        <input type="file" name="image" accept="image/*">
        @if(isset($item->image_path))
            <p class="mt-1 text-xs text-gray-600">Leave blank to keep existing.</p>
        @endif
    </div>

    <div class="flex items-center space-x-2">
        <input type="checkbox" name="is_available" value="1"
               @checked(old('is_available', $item->is_available ?? true))>
        <span>Available</span>
    </div>

    <button class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700">
        Save
    </button>
</div>
