<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['subcategories', 'items' => function ($q) {
            $q->where('is_available', true);
        }])->whereNull('parent_id')->orderBy('position')->get();

        foreach ($categories as $category) {
            foreach ($category->items as $item) {
                $item->image_path = $item->image_path 
                    ? Storage::url($item->image_path)
                    : null;
            }
        };

        return view('menu.index', compact('categories',));
    }

    public function preOrder()
    {
        $categories = Category::with([ 
            'items', 
            'subcategories' => function ($query) {
                $query->with('items');
            }])->whereNull('parent_id')->orderBy('position')->get();

        foreach ($categories as $category) {
            foreach ($category->items as $item) {
                $item->image_path = $item->image_path;
                    //? Storage::url($item->image_path)
                    //: null;
            }
        };
        foreach ($category->subcategories as $subcategory) {
            foreach ($subcategory->items as $item) {
                $item->image_path = $item->image_path 
                    ? Storage::url($item->image_path)
                    : null;
            }
        };


        $cart = session('cart', []);

        return view('menu.pre-order', compact('categories', 'cart'));
    }

    public function saveCart(Request $request)
    {
        session(['cart' => $request->cart]);
        return response()->json(['status' => 'saved']);
    }
}
