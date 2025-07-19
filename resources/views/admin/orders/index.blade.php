<x-layouts.app>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">All Orders</h2>

        <form method="GET" class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label for="status" class="block text-sm font-medium">Status</label>
                <select name="status" id="status" class="border rounded px-2 py-1 text-sm w-full">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div>
                <label for="from" class="block text-sm font-medium">From</label>
                <input type="date" name="from" id="from" value="{{ request('from') }}" class="border rounded px-2 py-1 text-sm w-full">
            </div>

            <div>
                <label for="to" class="block text-sm font-medium">To</label>
                <input type="date" name="to" id="to" value="{{ request('to') }}" class="border rounded px-2 py-1 text-sm w-full">
            </div>

            <div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded">Apply</button>
            </div>
        </form>

        <a href="{{ route('admin.orders.export', request()->all()) }}"
            class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded shadow">
            Export CSV
        </a>

        <a href="{{ route('admin.orders.export_pdf', request()->all()) }}"
            class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded shadow">
            Export PDF
        </a>




        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2">Order Code</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Total (₦)</th>
                        <th class="px-4 py-2">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-2 font-mono text-indigo-600">
                                <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->code }}</a>
                            </td>

                            <td class="px-4 py-2 capitalize">{{ $order->status }}
                                <p class="text-sm text-white capitalize bg-{{ $statusColor[$status] }}-500 rounded px-2 py-1 inline-block">
                                    {{ $status }}
                                </p>
                            </td>
                            <td class="px-4 py-2 font-semibold">₦{{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</x-layouts.app>
