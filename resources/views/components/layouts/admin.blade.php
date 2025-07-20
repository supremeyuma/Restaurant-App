<x-layouts.app>


        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            
            <!-- Top Navigation -->
            <x-admin-topbar/>

            <!-- Main Body -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
</x-layouts.app>