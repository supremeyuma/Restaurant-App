<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('order.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $order = Order::where('pickup_code', $request->code)->first();

        if (!$order) {
            return back()->withErrors(['code' => 'Order not found.']);
        }

        return view('order.track_result', compact('order'));
    }
}
