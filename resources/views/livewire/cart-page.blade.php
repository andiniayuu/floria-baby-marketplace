<div class="min-h-screen bg-gray-50">
  <div class="w-full max-w-7xl mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="mb-5">
      <h1 class="text-2xl font-bold text-gray-800">Shopping Cart</h1>
      <p class="text-gray-600 text-sm mt-1">
        @if(count($cart_items) > 0)
          {{ count($cart_items) }} {{ Str::plural('item', count($cart_items)) }} in your cart
        @else
          Your cart is empty
        @endif
      </p>
    </div>

    @if(count($cart_items) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      <!-- CART ITEMS SECTION -->
      <div class="lg:col-span-2 space-y-3">
        
        <!-- Select All Bar -->
        <div class="bg-white rounded-sm shadow-sm p-4 border border-gray-200">
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox"
                     wire:model.live="selectAll"
                     class="w-4 h-4 rounded border-gray-300 text-pink-500 focus:ring-pink-400 cursor-pointer">
              <span class="text-sm font-medium text-gray-700">
                Select All ({{ count($cart_items) }})
              </span>
            </label>
            
            @if(count($selectedItems) > 0)
            <button wire:click="removeSelectedItems"
                    wire:confirm="Remove {{ count($selectedItems) }} selected items from cart?"
                    class="text-red-600 hover:text-red-700 text-sm font-medium transition">
              Delete ({{ count($selectedItems) }})
            </button>
            @endif
          </div>
        </div>

        <!-- Cart Items -->
        <div class="bg-white rounded-sm shadow-sm border border-gray-200">
          
          <!-- Desktop Table Header -->
          <div class="hidden md:grid md:grid-cols-12 gap-4 px-5 py-3 bg-gray-50 border-b text-xs font-semibold text-gray-600 uppercase">
            <div class="col-span-5">Product</div>
            <div class="col-span-2 text-center">Price</div>
            <div class="col-span-2 text-center">Quantity</div>
            <div class="col-span-2 text-center">Subtotal</div>
            <div class="col-span-1 text-center">Action</div>
          </div>

          <!-- Items List -->
          <div class="divide-y divide-gray-100">
            @foreach ($cart_items as $item)
            
            <!-- Desktop View -->
            <div wire:key="desktop-{{ $item['product_id'] }}"
                 class="hidden md:grid md:grid-cols-12 gap-4 px-5 py-4 items-center hover:bg-gray-50 transition-colors {{ in_array($item['product_id'], $selectedItems) ? 'bg-pink-50 border-l-4 border-l-pink-400' : '' }}">
              
              <!-- Checkbox + Product -->
              <div class="col-span-5 flex items-center gap-3">
                <input type="checkbox"
                       wire:model.live="selectedItems"
                       value="{{ $item['product_id'] }}"
                       class="w-4 h-4 rounded border-gray-300 text-pink-500 focus:ring-pink-400 cursor-pointer flex-shrink-0">
                
                <div class="relative flex-shrink-0">
                  <img class="w-20 h-20 rounded-md object-cover border border-gray-200"
                       src="{{ url('storage', $item['image']) }}"
                       alt="{{ $item['name'] }}">
                  @if($item['quantity'] >= $item['stock'])
                  <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full font-semibold">
                    Low
                  </span>
                  @endif
                </div>
                
                <div class="flex-1 min-w-0">
                  <h3 class="text-sm text-gray-800 font-medium mb-1 line-clamp-2">
                    {{ $item['name'] }}
                  </h3>
                  <p class="text-xs text-gray-500">
                    Stock: {{ $item['stock'] }} available
                  </p>
                </div>
              </div>
              
              <!-- Price -->
              <div class="col-span-2 text-center">
                <span class="text-gray-800 font-semibold text-sm">
                  {{ Number::currency($item['unit_amount'], 'IDR') }}
                </span>
              </div>
              
              <!-- Quantity Controls -->
              <div class="col-span-2 flex items-center justify-center">
                <div class="inline-flex items-center border border-gray-300 rounded-sm">
                  <button wire:click="decreaseQty({{ $item['product_id'] }})"
                          class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors text-gray-600 border-r border-gray-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  
                  <input type="text" 
                         value="{{ $item['quantity'] }}"
                         class="w-12 h-8 text-center text-sm font-semibold border-0 focus:outline-none focus:ring-0"
                         readonly>
                  
                  <button wire:click="increaseQty({{ $item['product_id'] }})"
                          @if($item['quantity'] >= $item['stock']) disabled @endif
                          class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors text-gray-600 border-l border-gray-300 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Subtotal -->
              <div class="col-span-2 text-center">
                <span class="font-bold text-pink-600 text-base">
                  {{ Number::currency($item['total_amount'], 'IDR') }}
                </span>
              </div>
              
              <!-- Delete -->
              <div class="col-span-1 text-center">
                <button wire:click="removeItem({{ $item['product_id'] }})"
                        wire:confirm="Remove this item from cart?"
                        class="text-gray-400 hover:text-red-600 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Mobile View -->
            <div wire:key="mobile-{{ $item['product_id'] }}"
                 class="md:hidden p-4 {{ in_array($item['product_id'], $selectedItems) ? 'bg-pink-50 border-l-4 border-l-pink-400' : '' }}">
              
              <!-- Header -->
              <div class="flex items-center justify-between mb-3">
                <label class="flex items-center gap-2">
                  <input type="checkbox"
                         wire:model.live="selectedItems"
                         value="{{ $item['product_id'] }}"
                         class="w-4 h-4 rounded border-gray-300 text-pink-500">
                  <span class="text-xs text-gray-600">Select</span>
                </label>
                
                <button wire:click="removeItem({{ $item['product_id'] }})"
                        wire:confirm="Remove this item?"
                        class="text-gray-400 hover:text-red-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>

              <!-- Product Info -->
              <div class="flex gap-3 mb-3">
                <div class="relative flex-shrink-0">
                  <img class="w-20 h-20 rounded-md object-cover border border-gray-200"
                       src="{{ url('storage', $item['image']) }}"
                       alt="{{ $item['name'] }}">
                  @if($item['quantity'] >= $item['stock'])
                  <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded-full">
                    Low
                  </span>
                  @endif
                </div>

                <div class="flex-1 min-w-0">
                  <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2">
                    {{ $item['name'] }}
                  </h3>
                  <p class="text-xs text-gray-500 mb-1">
                    Stock: {{ $item['stock'] }}
                  </p>
                  <p class="text-sm font-semibold text-gray-800">
                    {{ Number::currency($item['unit_amount'], 'IDR') }}
                  </p>
                </div>
              </div>

              <!-- Quantity & Subtotal -->
              <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                <div class="inline-flex items-center border border-gray-300 rounded-sm">
                  <button wire:click="decreaseQty({{ $item['product_id'] }})"
                          class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 border-r border-gray-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  
                  <span class="w-10 text-center text-sm font-semibold">
                    {{ $item['quantity'] }}
                  </span>
                  
                  <button wire:click="increaseQty({{ $item['product_id'] }})"
                          @if($item['quantity'] >= $item['stock']) disabled @endif
                          class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 border-l border-gray-300 disabled:opacity-30">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>

                <div class="text-right">
                  <p class="text-xs text-gray-500">Subtotal</p>
                  <p class="font-bold text-base text-pink-600">
                    {{ Number::currency($item['total_amount'], 'IDR') }}
                  </p>
                </div>
              </div>
            </div>

            @endforeach
          </div>
        </div>

      </div>

      <!-- ORDER SUMMARY -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-sm shadow-sm border border-gray-200 sticky top-4">
          
          <!-- Header -->
          <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
              <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Order Summary
            </h2>
          </div>

          <!-- Summary Details -->
          <div class="px-5 py-4 space-y-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Total Items</span>
              <span class="font-semibold text-gray-800">{{ count($cart_items) }}</span>
            </div>

            <div class="flex justify-between pb-3 border-b border-gray-200">
              <span class="text-gray-600">Selected Items</span>
              <span class="font-semibold text-pink-600">{{ count($selectedItems) }}</span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-600">Cart Total</span>
              <span class="font-semibold text-gray-800">
                {{ Number::currency($this->cartTotal, 'IDR') }}
              </span>
            </div>

            @if(count($selectedItems) > 0)
            <div class="flex justify-between pt-3 border-t border-gray-200">
              <span class="font-semibold text-gray-800">Checkout Total</span>
              <span class="font-bold text-lg text-pink-600">
                {{ Number::currency($this->checkoutTotal, 'IDR') }}
              </span>
            </div>
            @endif
          </div>

          <!-- Checkout Button -->
          <div class="px-5 py-4 border-t border-gray-200">
            @if(count($selectedItems) > 0)
            <button wire:click="checkoutSelected"
                    class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 rounded-sm transition-all shadow-sm hover:shadow-md text-sm">
              Checkout ({{ count($selectedItems) }})
            </button>
            <p class="text-xs text-center text-gray-500 mt-2">
              🔒 Secure checkout
            </p>
            @else
            <button disabled
                    class="w-full bg-gray-300 text-gray-500 font-semibold py-3 rounded-sm cursor-not-allowed text-sm">
              Select items to checkout
            </button>
            <p class="text-xs text-center text-gray-500 mt-2">
              Please select at least one item
            </p>
            @endif
          </div>

          <!-- Continue Shopping -->
          <div class="px-5 pb-4">
            <a href="{{ route('products') }}"
               wire:navigate
               class="block w-full text-center border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-sm hover:bg-gray-50 transition-all text-sm">
              Continue Shopping
            </a>
          </div>
        </div>
      </div>

    </div>
    @else
    <!-- Empty Cart -->
    <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-12 text-center">
      <div class="max-w-md mx-auto">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Your cart is empty</h3>
        <p class="text-gray-600 mb-6 text-sm">
          Looks like you haven't added any items to your cart yet.
        </p>
        <a href="{{ route('products') }}"
           wire:navigate
           class="inline-flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-3 rounded-sm transition shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Start Shopping
        </a>
      </div>
    </div>
    @endif

  </div>

  <style>
/* Custom scrollbar untuk tampilan lebih bersih */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, color;
  transition-duration: 150ms;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Line clamp untuk teks panjang */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

</div>

