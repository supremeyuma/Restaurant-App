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
                            <button @click="addToCart(item)"
                                class="mt-2 text-sm px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Add to Cart
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Cart Preview --}}
        <div class="fixed bottom-4 right-4 bg-white shadow-xl rounded-lg p-4 w-72 border"
             x-show="cart.length > 0">
            <h2 class="font-bold text-lg mb-2">Cart</h2>
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex justify-between items-center mb-2">
                    <span x-text="item.name + ' x' + item.qty"></span>
                    <span x-text="'₦' + (item.qty * item.price).toLocaleString()"></span>
                </div>
            </template>
            <div class="mt-2 font-semibold">
                Total: ₦<span x-text="cartTotal()"></span>
            </div>
            <div class="mt-3 text-right">
                <form method="GET" action="{{ url('/cart') }}">
                    <input type="hidden" name="from_menu" value="1">
                    <button type="submit"
                        @click="saveCart()"
                        class="text-sm px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Proceed to Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine Data --}}
    <script>
        function cartHandler() {
            return {
                categories: @json($categories),
                cart: @json($cart),

                addToCart(item) {
                    let found = this.cart.find(i => i.id === item.id)
                    if (found) {
                        found.qty++
                    } else {
                        this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1 })
                    }
                },

                cartTotal() {
                    return this.cart.reduce((t, i) => t + (i.price * i.qty), 0).toLocaleString()
                },

                saveCart() {
                    fetch('{{ route('cart.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ cart: this.cart })
                    });
                }
            }
        }
    </script>
</x-layouts.guest>
