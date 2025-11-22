<x-layouts.guest>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <div class="w-full px-3 sm:px-4 lg:px-8 py-4" x-data="menuHandler()" x-init="init()">

            {{-- Page Title --}}
            <h1 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Menu</h1>

            {{-- Category Slider --}}
            <div class="mb-4 sm:mb-6 overflow-x-auto whitespace-nowrap modern-scroll drag-scrollable" x-scroll-drag>
                <div class="inline-flex space-x-1 sm:space-x-2 p-1">
                    <template x-for="category in categories" :key="category.id">
                        <button 
                            x-show="true"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            @click="setActiveCategory(category)"
                            :class="{
                                'bg-indigo-600 text-white shadow-lg': activeCategory && activeCategory.id === category.id,
                                'bg-gray-200 text-gray-700 hover:bg-gray-300': !activeCategory || activeCategory.id !== category.id
                            }"
                            class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 flex-shrink-0">
                            <span x-text="category.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Subcategory Slider --}}
            <div 
                x-show="activeCategory && activeCategory.subcategories.length > 0"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mb-4 sm:mb-6 overflow-x-auto whitespace-nowrap modern-scroll drag-scrollable"
                x-scroll-drag
            >
                <h2 class="text-base sm:text-lg font-semibold mb-2" x-text="activeCategory ? activeCategory.name + ' Subcategories' : ''"></h2>

                <div class="inline-flex space-x-1 sm:space-x-2 p-1">
                    {{-- All Items Button --}}
                    <button 
                        x-show="true"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        @click="setActiveSubcategory(null)"
                        :class="{
                            'bg-indigo-600 text-white shadow-lg': activeSubcategory === null,
                            'bg-gray-200 text-gray-700 hover:bg-gray-300': activeSubcategory !== null
                        }"
                        class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 flex-shrink-0"
                    >
                        <span x-text="'All ' + (activeCategory ? activeCategory.name : '') + ' Items'"></span>
                    </button>

                    {{-- Subcategory Buttons --}}
                    <template x-for="subcategory in activeCategory.subcategories" :key="subcategory.id">
                        <button 
                            x-show="true"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            @click="setActiveSubcategory(subcategory)"
                            :class="{
                                'bg-indigo-600 text-white shadow-lg': activeSubcategory && activeSubcategory.id === subcategory.id,
                                'bg-gray-200 text-gray-700 hover:bg-gray-300': !activeSubcategory || activeSubcategory.id !== subcategory.id
                            }"
                            class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 flex-shrink-0"
                        >
                            <span x-text="subcategory.name"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Items Display --}}
            <div class="mb-5 sm:mb-6">
                <h2 class="text-base sm:text-lg font-semibold mb-2" x-text="displayedItemsTitle()"></h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <template x-for="item in filteredItems" :key="item.id">
                        <div
                            x-show="true"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            class="border rounded-lg shadow-sm p-2 sm:p-3 bg-white flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-200 relative"
                        >
                            <img :src="item.image_path ? '/storage/' + item.image_path : '/path/to/default/image.png'" 
                                 class="h-24 sm:h-32 w-full object-cover rounded mb-1 sm:mb-2 item-image"
                                 alt="Item Image"
                                 :id="'item-image-' + item.id"
                            >
                            <h3 class="font-semibold text-sm sm:text-base" x-text="item.name"></h3>
                            <p class="text-xs sm:text-sm text-gray-500 flex-grow" x-text="'₦' + item.price.toLocaleString()"></p>
                            <p class="text-xs text-gray-400" x-text="item.wait_time_minutes + ' min wait'"></p>
                            <button @click="flyToCart(item, $event)"
                                    class="mt-1 sm:mt-2 text-xs sm:text-sm px-2 sm:px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 hover:shadow-md transition-all duration-200 self-start">
                                Add to Cart
                            </button>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="filteredItems.length === 0" 
                         class="col-span-full text-center text-gray-400 py-8 sm:py-10 text-sm animate-pulse">
                        No items found for this selection.
                    </div>
                </div>
            </div>

            {{-- Cart Preview --}}
            <div 
                class="fixed bottom-4 right-4 bg-white shadow-xl rounded-lg p-3 sm:p-4 w-64 sm:w-72 border z-50"
                x-show="cart.length > 0"
                x-transition:enter="transition transform duration-300"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition transform duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-6"
            >
                <h2 class="font-bold text-base sm:text-lg mb-2">Cart <span class="ml-2 text-xs text-gray-500" x-text="'(' + cart.length + ' items)'"></span></h2>

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

                {{-- Total --}}
                <div class="mt-2 font-semibold text-sm sm:text-base">
                    Total: ₦<span x-text="cartTotal()"></span>
                </div>

                {{-- Checkout Button --}}
                <div class="mt-2 sm:mt-3 text-right">
                    <form method="GET" action="{{ url('pre-order/cart') }}">
                        <button type="submit"
                                class="text-xs sm:text-sm px-3 sm:px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 hover:shadow-md transition-all duration-200">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script>
        function menuHandler() {
            return {
                categories: @json($categories),
                activeCategory: null,
                activeSubcategory: null,
                cart: @json(session('cart', [])),

                init() {
                    if (this.categories.length > 0) {
                        this.activeCategory = this.categories[0];
                        this.activeSubcategory = null;
                    }
                },

                setActiveCategory(category) {
                    this.activeCategory = category;
                    this.activeSubcategory = null;
                },

                setActiveSubcategory(subcategory) {
                    this.activeSubcategory = subcategory;
                },

                get filteredItems() {
                    if (!this.activeCategory) return this.categories.flatMap(cat => cat.items || []);
                    if (this.activeSubcategory) return this.activeSubcategory.items;
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

                cartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0).toLocaleString();
                },

                addToCart(item) {
                    this._addCartItem(item);
                    this.syncCartSession();
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.syncCartSession();
                },

                increaseQty(index) {
                    this.cart[index].qty += 1;
                    this.syncCartSession();
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) this.cart[index].qty -= 1;
                    this.syncCartSession();
                },

                flyToCart(item, event) {
                    this._addCartItem(item);
                    this.syncCartSession();

                    // Clone image for fly effect
                    const img = event.currentTarget.closest('div').querySelector('img');
                    const rect = img.getBoundingClientRect();
                    const clone = img.cloneNode(true);
                    clone.style.position = 'fixed';
                    clone.style.top = rect.top + 'px';
                    clone.style.left = rect.left + 'px';
                    clone.style.width = rect.width + 'px';
                    clone.style.height = rect.height + 'px';
                    clone.style.transition = 'all 0.8s ease-in-out';
                    clone.style.zIndex = 1000;
                    clone.style.pointerEvents = 'none';
                    document.body.appendChild(clone);

                    const cartEl = document.querySelector('.fixed.bottom-4.right-4');
                    const cartRect = cartEl.getBoundingClientRect();

                    requestAnimationFrame(() => {
                        clone.style.top = cartRect.top + 10 + 'px';
                        clone.style.left = cartRect.left + 10 + 'px';
                        clone.style.width = '30px';
                        clone.style.height = '30px';
                        clone.style.opacity = 0.5;
                        clone.style.transform = 'scale(0.5)';
                    });

                    clone.addEventListener('transitionend', () => clone.remove());
                },

                _addCartItem(item) {
                    const existing = this.cart.find(i => i.id === item.id);
                    if (existing) existing.qty += 1;
                    else this.cart.push({...item, qty: 1});
                },

                syncCartSession() {
                    fetch('/cart/save', {
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
