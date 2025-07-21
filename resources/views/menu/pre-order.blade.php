<x-layouts.guest>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <div class="w-full px-3 sm:px-4 lg:px-8 py-4" x-data="menuHandler()">

            <h1 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Menu</h1>

            {{-- Category Slider (Top Level) --}}
            <div class="mb-4 sm:mb-6 overflow-x-auto whitespace-nowrap modern-scroll drag-scrollable" x-scroll-drag>
                <div class="inline-flex space-x-1 sm:space-x-2 p-1">
                    <template x-for="category in categories" :key="category.id">
                        <button @click="setActiveCategory(category)"
                                :class="{ 'bg-indigo-600 text-white': activeCategory && activeCategory.id === category.id, 'bg-gray-200 text-gray-700 hover:bg-gray-300': !activeCategory || activeCategory.id !== category.id }"
                                class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-colors duration-200 flex-shrink-0">
                            <span x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Subcategory Slider --}}
            <div x-show="activeCategory && activeCategory.subcategories.length > 0" x-transition class="mb-4 sm:mb-6 overflow-x-auto whitespace-nowrap modern-scroll drag-scrollable" x-scroll-drag>
                <h2 class="text-base sm:text-lg font-semibold mb-2" x-text="activeCategory ? activeCategory.name + ' Subcategories' : ''"></h2>
                <div class="inline-flex space-x-1 sm:space-x-2 p-1">
                    <button @click="setActiveSubcategory(null)"
                            :class="{ 'bg-indigo-600 text-white': activeSubcategory === null, 'bg-gray-200 text-gray-700 hover:bg-gray-300': activeSubcategory !== null }"
                            class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-colors duration-200 flex-shrink-0"
                            x-text="'All ' + (activeCategory ? activeCategory.name : '') + ' Items'">
                    </button>
                    <template x-for="subcategory in activeCategory.subcategories" :key="subcategory.id">
                        <button @click="setActiveSubcategory(subcategory)"
                                :class="{ 'bg-indigo-600 text-white': activeSubcategory && activeSubcategory.id === subcategory.id, 'bg-gray-200 text-gray-700 hover:bg-gray-300': !activeSubcategory || activeSubcategory.id !== subcategory.id }"
                                class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-colors duration-200 flex-shrink-0">
                            <span x-text="subcategory.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Items Display --}}
            <div class="mb-5 sm:mb-6">
                <h2 class="text-base sm:text-lg font-semibold mb-2" x-text="displayedItemsTitle()"></h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    <template x-for="item in filteredItems" :key="item.id">
                        <div class="border rounded shadow p-2 sm:p-3 bg-white flex flex-col">
                            <img :src="item.image_path ? '/storage/' + item.image_path : '/path/to/default/image.png'" class="h-24 sm:h-32 w-full object-cover rounded mb-1 sm:mb-2" alt="Item Image">
                            <h3 class="font-semibold text-sm sm:text-base" x-text="item.name"></h3>
                            <p class="text-xs sm:text-sm text-gray-500 flex-grow" x-text="'₦' + item.price.toLocaleString()"></p>
                            <p class="text-xs text-gray-400" x-text="item.wait_time_minutes + ' min wait'"></p>
                            <button @click="addToCart(item)"
                                class="mt-1 sm:mt-2 text-xs sm:text-sm px-2 sm:px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 self-start">
                                Add to Cart
                            </button>
                        </div>
                    </template>
                    <div x-show="filteredItems.length === 0" class="col-span-full text-center text-gray-500 py-8 sm:py-10 text-sm">
                        No items found for this selection.
                    </div>
                </div>
            </div>

            {{-- Cart Preview --}}
            <div class="fixed bottom-4 right-4 bg-white shadow-xl rounded-lg p-3 sm:p-4 w-64 sm:w-72 border z-50"
                x-show="cart.length > 0">
                <h2 class="font-bold text-base sm:text-lg mb-2">Cart</h2>
                <template x-for="(item, index) in cart" :key="index">
                    <div class="mb-2 border-b pb-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium" x-text="item.name"></span>
                            <button @click="removeItem(index)" class="text-red-500 text-xs hover:text-red-700">
                                🗑️ Remove
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-1 text-xs sm:text-sm">
                            <div class="flex items-center gap-1 sm:gap-2">
                                <button @click="decreaseQty(index)"
                                        class="px-2 py-0.5 bg-gray-200 rounded hover:bg-gray-300 text-xs">-</button>
                                <span x-text="item.qty"></span>
                                <button @click="increaseQty(index)"
                                        class="px-2 py-0.5 bg-gray-200 rounded hover:bg-gray-300 text-xs">+</button>
                            </div>
                            <span class="text-right font-semibold" x-text="'₦' + (item.qty * item.price).toLocaleString()"></span>
                        </div>
                    </div>
                </template>

                <div class="mt-2 font-semibold text-sm sm:text-base">
                    Total: ₦<span x-text="cartTotal()"></span>
                </div>
                <div class="mt-2 sm:mt-3 text-right">
                    <form method="GET" action="{{ url('/cart') }}">
                        <input type="hidden" name="from_menu" value="1">
                        <button type="submit"
                            @click="saveCart()"
                            class="text-xs sm:text-sm px-3 sm:px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine Data (Unchanged) --}}
    <script>
        function menuHandler() {
            return {
                categories: @json($categories),
                cart: @json($cart),
                activeCategory: null,
                activeSubcategory: null,

                init() {
                    if (this.categories.length > 0) {
                        this.activeCategory = this.categories[0];
                        if (this.activeCategory.subcategories.length > 0) {
                            this.activeSubcategory = null;
                        }
                    }
                },

                setActiveCategory(category) {
                    this.activeCategory = category;
                    if (category.subcategories.length > 0) {
                        this.activeSubcategory = null;
                    } else {
                        this.activeSubcategory = null;
                    }
                },

                setActiveSubcategory(subcategory) {
                    this.activeSubcategory = subcategory;
                },

                get filteredItems() {
                    if (!this.activeCategory) {
                        return this.categories.flatMap(cat => cat.items || []);
                    }

                    if (this.activeSubcategory) {
                        return this.activeSubcategory.items;
                    }

                    let items = [...(this.activeCategory.items || [])];
                    this.activeCategory.subcategories.forEach(subcat => {
                        items = items.concat(subcat.items || []);
                    });
                    return items;
                },

                displayedItemsTitle() {
                    if (!this.activeCategory) return 'All Menu Items';
                    if (this.activeSubcategory) return this.activeSubcategory.name + ' Items';
                    return this.activeCategory.name + ' Items';
                },

                addToCart(item) {
                    let found = this.cart.find(i => i.id === item.id)
                    if (found) {
                        found.qty++
                    } else {
                        this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1, image_path: item.image_path, wait_time_minutes: item.wait_time_minutes })
                    }
                },

                increaseQty(index) {
                    this.cart[index].qty++
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--
                    } else {
                        this.removeItem(index)
                    }
                },

                removeItem(index) {
                    this.cart.splice(index, 1)
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
