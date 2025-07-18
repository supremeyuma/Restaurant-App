<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['items' => function ($q) {
            $q->where('is_available', true);
        }])->orderBy('position')->get();

        $cart = session('cart', []);

        return view('menu.index', compact('categories', 'cart'));
    }

    public function saveCart(Request $request)
    {
        session(['cart' => $request->cart]);
        return response()->json(['status' => 'saved']);
    }
}
