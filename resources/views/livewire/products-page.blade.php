<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="py-10 bg-white font-poppins rounded-xl">
    <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
      <div class="flex flex-wrap mb-24 -mx-3">

        <!-- SIDEBAR -->
        <div class="w-full pr-2 lg:w-1/4 lg:block space-y-5">

          <!-- Categories -->
          <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800">Categories</h2>
            <div class="w-12 pb-2 mb-5 border-b border-rose-500"></div>

            <ul class="space-y-3">
              @foreach ($categories as $category)
                <li wire:key="{{ $category->id }}">
                  <label class="flex items-center gap-2 text-gray-700">
                    <input
                      type="checkbox"
                      wire:model.live="selected_categories"
                      value="{{ $category->id }}"
                      class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400"
                    >
                    <span class="text-base font-medium">{{ $category->name }}</span>
                  </label>
                </li>
              @endforeach
            </ul>
          </div>

          <!-- Brand -->
          <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800">Brand</h2>
            <div class="w-12 pb-2 mb-5 border-b border-rose-500"></div>

            <ul class="space-y-3">
              @foreach ($brands as $brand)
                <li wire:key="{{ $brand->id }}">
                  <label class="flex items-center gap-2 text-gray-700">
                    <input
                      type="checkbox"
                      wire:model.live="selected_brands"
                      value="{{ $brand->id }}"
                      class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400"
                    >
                    <span class="text-base font-medium">{{ $brand->name }}</span>
                  </label>
                </li>
              @endforeach
            </ul>
          </div>

          <!-- Product Status -->
          <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800">Product Status</h2>
            <div class="w-12 pb-2 mb-5 border-b border-rose-500"></div>

            <ul class="space-y-3">
              <li>
                <label class="flex items-center gap-2 text-gray-700">
                  <input
                    type="checkbox"
                    wire:model.live="featured"
                    class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400"
                  >
                  <span class="text-base font-medium">Featured Products</span>
                </label>
              </li>
              <li>
                <label class="flex items-center gap-2 text-gray-700">
                  <input
                    type="checkbox"
                    wire:model.live="on_sale"
                    class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400"
                  >
                  <span class="text-base font-medium">On Sale</span>
                </label>
              </li>
            </ul>
          </div>

          <!-- Price -->
          <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800">Price</h2>
            <div class="w-12 pb-2 mb-5 border-b border-rose-500"></div>

            <div>
              <div class="mb-2 text-lg font-semibold text-gray-800">
                {{ Number::currency($price_range, 'IDR') }}
              </div>

              <input
                type="range"
                wire:model.live="price_range"
                max="500000"
                step="1000"
                class="w-full h-1 mb-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-rose-500"
              >

              <div class="flex justify-between text-xs font-semibold text-gray-500">
                <span>{{ Number::currency(1000, 'IDR') }}</span>
                <span>{{ Number::currency(500000, 'IDR') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- CONTENT -->
        <div class="w-full px-3 lg:w-3/4">

          <!-- Sort -->
          <div class="mb-5">
            <div class="flex items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm">
              <select wire:model.live="sort" class="w-44 text-base bg-white border border-gray-300 rounded-lg text-gray-700 focus:ring-rose-400">
                <option value="latest">Sort by latest</option>
                <option value="price">Sort by price</option>
              </select>
            </div>
          </div>

          <!-- Products -->
          <div class="flex flex-wrap -mx-3">
            @foreach ($products as $product)
              <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key="{{ $product->id }}">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden h-full flex flex-col">

                  {{-- Image Product --}}
                  <a href="/products/{{ $product->slug }}">
                    <img
                      src="{{ url('storage', $product->images[0]) }}"
                      alt="{{ $product->name }}"
                      class="object-cover w-full h-56"
                    >
                  </a>

                  <div class="p-4 flex flex-col flex-1">
                    {{-- nama product --}}
                    <h3 class="mb-2 text-base font-semibold text-gray-800 line-clamp-2">
                      {{ $product->name }}
                    </h3>

                    {{-- harga --}}
                    <span class="text-lg font-bold text-green-600 tracking-wide">
                      Rp{{ number_format($product->price, 0, ',', '.') }}
                    </span>
                  </div>

                  <div class="mt-4 border-t border-gray-100"></div>
                  <div class="mt-auto pt-3 pb-2 flex justify-center">

                  <button
                    wire:key="add-to-cart-{{ $product->id }}"
                    wire:click.prevent="addToCart({{ $product->id }})"
                    wire:loading.attr="disabled"
                    wire:target="addToCart({{ $product->id }})"
                    class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 transition disabled:opacity-60">

                    <!-- ICON -->
                    <svg
                      wire:loading.remove
                      wire:target="addToCart({{ $product->id }})"
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4"
                      fill="currentColor"
                      viewBox="0 0 16 16">
                      <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                  
                    <!-- TEXT NORMAL -->
                    <span
                      wire:loading.remove
                      wire:target="addToCart({{ $product->id }})">
                      Add to Cart
                    </span>
                  
                    <!-- TEXT LOADING -->
                    <span
                      wire:loading
                      wire:target="addToCart({{ $product->id }})">
                      Adding...
                    </span>
                  </button>
                </div>

                </div>
              </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="flex justify-end mt-6">
            {{ $products->links() }}
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
