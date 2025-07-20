<x-layouts.guest>
    <div class="max-w-6xl mx-auto py-6 px-4" x-data="cartHandler()">
        <h1 class="text-2xl font-bold mb-4">Menu</h1>

        {{-- Categories & Items --}}
        <template x-for="cat in categories" :key="cat.id">
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2" x-text="cat.name"></h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <template x-for="item in cat.items" :key="item.id">
                        <div class="border rounded shadow p-3 bg-white">
                            <img :src=" item.image_path" class="h-32 w-full object-cover rounded mb-2" x-show="item.image_path">
                            <h3 class="font-semibold" x-text="item.name"></h3>
                            <p class="text-sm text-gray-500" x-text="'₦' + item.price.toLocaleString()"></p>
                            <p class="text-xs text-gray-400" x-text="item.wait_time_minutes + ' min wait'"></p>
                            
                        </div>
                    </template>
                </div>
            </div>
        </template>



    {{-- Alpine Data --}}
    <script>
        function cartHandler() {
            return {
                categories: @json($categories),
                  }
            }
    </script>
</x-layouts.guest>
