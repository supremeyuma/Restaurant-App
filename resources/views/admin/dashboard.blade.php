<x-layouts.app>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 bg-white shadow rounded flex items-center gap-4">
            <div class="bg-indigo-100 text-indigo-700 p-2 rounded-full">
                <x-heroicon-o-shopping-cart class="w-5 h-5" />
            </div>
            <div>
                <h3 class="text-sm text-gray-500">Orders Today</h3>
                <p class="text-xl font-bold">{{ $ordersToday }}</p>
            </div>
        </div>

            <div class="p-4 bg-white shadow rounded">
                <h3 class="text-sm text-gray-500">Sales Today</h3>
                <p class="text-xl font-bold">₦{{ number_format($salesToday, 2) }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded">
                <h3 class="text-sm text-gray-500">Total Orders</h3>
                <p class="text-xl font-bold">{{ $totalOrders }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded">
                <h3 class="text-sm text-gray-500">Total Sales</h3>
                <p class="text-xl font-bold">₦{{ number_format($totalSales, 2) }}</p>
            </div>
        </div>

        <!-- Status breakdown -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Order Status Breakdown</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($statusCounts as $status => $count)
                    <div class="border rounded px-4 py-2 text-center">
                        <p class="text-sm text-gray-600 capitalize">{{ $status }}</p>
                        <p class="text-lg font-bold">{{ $count }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white p-4 rounded shadow overflow-x-auto">
            <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>
            <table class="min-w-full text-sm">
                <thead class="text-left">
                    <tr>
                        <th class="py-2">Code</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr class="border-t">
                            <td class="py-2 font-mono">{{ $order->pickup_code }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>₦{{ number_format($order->total, 2) }}</td>
                            <td class="capitalize">{{ $order->status }}</td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-2 text-center text-gray-500">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
