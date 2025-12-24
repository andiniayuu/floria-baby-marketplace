<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- CART ITEMS -->
    <div class="lg:col-span-8 space-y-4">

      <!-- DESKTOP TABLE -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden hidden md:block">
        <table class="w-full">
          <thead class="bg-slate-100 text-slate-600 text-sm">
            <tr>
              <th class="px-6 py-4 text-left">Product</th>
              <th class="px-6 py-4 text-left">Price</th>
              <th class="px-6 py-4 text-center">Quantity</th>
              <th class="px-6 py-4 text-left">Total</th>
              <th class="px-6 py-4"></th>
            </tr>
          </thead>

          <tbody class="divide-y">
            @forelse ($cart_items as $item)
            <tr wire:key="{{ $item['product_id'] }}">
              <td class="px-6 py-4">
                <div class="flex items-start gap-4">
                  <img class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                       src="{{ url('storage', $item['image']) }}">
                  <span class="font-medium text-slate-800
         line-clamp-1 md:line-clamp-2
         max-w-[240px] md:max-w-[300px]" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                </div>
              </td>

              <td class="px-6 py-4 text-slate-600">
                {{ Number::currency($item['unit_amount'], 'IDR') }}
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                  <button wire:click="decreaseQty({{ $item['product_id'] }})"
                    class="w-8 h-8 border rounded-lg hover:bg-slate-100">−</button>

                  <span class="w-6 text-center">{{ $item['quantity'] }}</span>

                  <button wire:click="increaseQty({{ $item['product_id'] }})"
                    class="w-8 h-8 border rounded-lg hover:bg-slate-100">+</button>
                </div>
              </td>

              <td class="px-6 py-4 font-semibold">
                {{ Number::currency($item['total_amount'], 'IDR') }}
              </td>

              <td class="px-6 py-4">
                <button wire:click="removeItem({{ $item['product_id'] }})"
                  class="text-red-500 hover:text-red-700 text-sm">
                  <span wire:loading.remove
                        wire:target="removeItem({{ $item['product_id'] }})">
                    Remove
                  </span>
                  <span wire:loading
                        wire:target="removeItem({{ $item['product_id'] }})">
                    Removing...
                  </span>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-12 text-slate-400 text-lg">
                No items available in cart
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- MOBILE CARD VIEW -->
      <div class="md:hidden space-y-4">
        @foreach ($cart_items as $item)
        <div class="bg-white rounded-xl shadow-sm p-4">
          <div class="flex gap-4">
            <img class="w-20 h-20 rounded-lg object-cover"
                 src="{{ url('storage', $item['image']) }}">

            <div class="flex-1">
              <h3 class="font-semibold">{{ $item['name'] }}</h3>
              <p class="text-sm text-slate-500">
                {{ Number::currency($item['unit_amount'], 'IDR') }}
              </p>

              <div class="flex items-center justify-between mt-3">
                <div class="flex items-center gap-2">
                  <button wire:click="decreaseQty({{ $item['product_id'] }})"
                    class="w-8 h-8 border rounded-lg">−</button>

                  <span>{{ $item['quantity'] }}</span>

                  <button wire:click="increaseQty({{ $item['product_id'] }})"
                    class="w-8 h-8 border rounded-lg">+</button>
                </div>

                <span class="font-semibold">
                  {{ Number::currency($item['total_amount'], 'IDR') }}
                </span>
              </div>

              <button wire:click="removeItem({{ $item['product_id'] }})"
                class="mt-3 text-red-500 text-sm">
                Remove
              </button>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- SUMMARY -->
    <div class="lg:col-span-4">
      <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

        <div class="space-y-2 text-sm text-slate-600">
          <div class="flex justify-between">
            <span>Subtotal</span>
            <span>{{ Number::currency($grand_total, 'IDR') }}</span>
          </div>
          <div class="flex justify-between">
            <span>Shipping</span>
            <span>Free</span>
          </div>
          <div class="flex justify-between">
            <span>Tax</span>
            <span>{{ Number::currency(0, 'IDR') }}</span>
          </div>
        </div>

        <hr class="my-4">

        <div class="flex justify-between font-semibold text-lg">
          <span>Total</span>
          <span>{{ Number::currency($grand_total, 'IDR') }}</span>
        </div>

        @if ($cart_items)
        <button
          class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
          Checkout
        </button>
        @endif
      </div>
    </div>

  </div>
</div>
