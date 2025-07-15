<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
{
    $items = Item::with('category')
        ->orderBy('category_id')
        ->orderBy('name')
        ->get();

    return view('admin.items.index', compact('items'));
}

public function create()
{
    $categories = Category::orderBy('name')->pluck('name', 'id');
    return view('admin.items.create', compact('categories'));
}

public function store(StoreItemRequest $request)
{
    $data = $request->validated();

    if ($request->hasFile('image')) {
        $data['image_path'] = $request->file('image')
            ->store('uploads/items', 'public');
    }

    $data['is_available'] = $request->boolean('is_available', true);

    Item::create($data);

    return redirect()->route('admin.items.index')
        ->with('success', 'Item created.');
}

public function edit(Item $item)
{
    $categories = Category::orderBy('name')->pluck('name', 'id');
    return view('admin.items.edit', compact('item', 'categories'));
}

public function update(UpdateItemRequest $request, Item $item)
{
    $data = $request->validated();

    if ($request->hasFile('image')) {
        // delete old if exists
        if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }
        $data['image_path'] = $request->file('image')
            ->store('uploads/items', 'public');
    }

    $data['is_available'] = $request->boolean('is_available', false);

    $item->update($data);

    return redirect()->route('admin.items.index')
        ->with('success', 'Item updated.');
}

public function destroy(Item $item)
{
    if ($item->image_path) {
        Storage::disk('public')->delete($item->image_path);
    }
    $item->delete();

    return back()->with('success', 'Item deleted.');
}

}
