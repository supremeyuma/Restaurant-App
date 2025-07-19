<aside class="w-64 bg-white dark:bg-gray-900 shadow-md min-h-screen hidden md:block">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-lg font-bold text-gray-800 dark:text-white">
            Admin Panel
        </h1>
    </div>

    <nav class="mt-4 space-y-2 px-4 text-sm text-gray-700 dark:text-gray-300">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 dark:bg-gray-800 font-semibold' : '' }}">
            Dashboard
        </a>

        <!-- Orders -->
        <div>
            <p class="text-xs uppercase font-semibold mt-4 mb-2 text-gray-500 dark:text-gray-400">Orders</p>
            <a href="{{ route('admin.orders.index') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.orders.index') ? 'bg-indigo-100 dark:bg-gray-800 font-semibold' : '' }}">
                Manage Orders
            </a>
            <a href="{{ route('admin.orders.export') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800">
                Export CSV
            </a>
            <a href="{{ route('admin.orders.export_pdf') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800">
                Export PDF
            </a>
        </div>

        <!-- QR Scanner -->
        <div>
            <p class="text-xs uppercase font-semibold mt-4 mb-2 text-gray-500 dark:text-gray-400">Scan</p>
            <a href="{{ route('admin.scan') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.scan') ? 'bg-indigo-100 dark:bg-gray-800 font-semibold' : '' }}">
                QR Scanner
            </a>
        </div>

        <!-- Settings -->
        <div>
            <p class="text-xs uppercase font-semibold mt-4 mb-2 text-gray-500 dark:text-gray-400">Settings</p>
            <a href="{{ route('admin.settings.edit') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.settings.edit') ? 'bg-indigo-100 dark:bg-gray-800 font-semibold' : '' }}">
                Site Settings
            </a>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 rounded hover:bg-red-100 dark:hover:bg-red-900 text-red-700 dark:text-red-400 font-semibold">
                Logout
            </button>
        </form>
    </nav>
</aside>
