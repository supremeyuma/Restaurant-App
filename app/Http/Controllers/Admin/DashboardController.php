<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();
        $ordersToday = Order::where('created_at', '>=', $today)->count();
        $salesToday = Order::where('created_at', '>=', $today)->sum('total');

        $totalOrders = Order::count();
        $totalSales = Order::sum('total');

        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentOrders = Order::with('items')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'ordersToday', 'salesToday', 'totalOrders', 'totalSales', 'statusCounts', 'recentOrders'
        ));
    }
}
