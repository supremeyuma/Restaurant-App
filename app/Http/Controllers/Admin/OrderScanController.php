<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderScanController extends Controller
{
    public function scanner()
    {
        return view('admin.scanner');
    }

    public function result($code)
    {
        $order = Order::where('pickup_code', $code)->with('items.item')->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function complete(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();

        if ($order) {
            $order->status = 'completed';
            $order->save();
        }

        return redirect()->back()->with('success', 'Order marked as completed.');
    }
}
