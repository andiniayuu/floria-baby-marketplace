<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
<section class="py-10 bg-white font-poppins rounded-xl">
    <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
      <div class="flex flex-wrap mb-24 -mx-3">

       <!-- SIDEBAR -->
<div class="w-full pr-2 lg:w-1/4 space-y-8">

  <!-- Categories -->
  <div>
    <div class="w-12 pb-2 mb-3 border-b border-rose-500"></div>
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
              class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400">
            <span class="text-base font-medium">{{ $category->name }}</span>
          </label>
        </li>
      @endforeach
    </ul>
  </div>

  <!-- Brand -->
  <div>
    <div class="w-12 pb-2 mb-3 border-b border-rose-500"></div>
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
              class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400">
            <span class="text-base font-medium">{{ $brand->name }}</span>
          </label>
        </li>
      @endforeach
    </ul>
  </div>

  <!-- Product Status -->
  <div>
    <div class="w-12 pb-2 mb-3 border-b border-rose-500"></div>
    <h2 class="text-2xl font-bold text-gray-800">Product Status</h2>
    <div class="w-12 pb-2 mb-5 border-b border-rose-500"></div>

    <ul class="space-y-3">
      <li>
        <label class="flex items-center gap-2 text-gray-700">
          <input
            type="checkbox"
            wire:model.live="featured"
            class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400">
          <span class="text-base font-medium">Featured Products</span>
        </label>
      </li>
      <li>
        <label class="flex items-center gap-2 text-gray-700">
          <input
            type="checkbox"
            wire:model.live="on_sale"
            class="w-4 h-4 text-rose-500 border-gray-300 rounded focus:ring-rose-400">
          <span class="text-base font-medium">On Sale</span>
        </label>
      </li>
    </ul>
  </div>

  <!-- Price -->
  <div>
    <div class="w-12 pb-2 mb-3 border-b border-rose-500"></div>
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
        class="w-full accent-rose-500">

      <div class="flex justify-between text-xs font-semibold text-gray-500 mt-1">
        <span>{{ Number::currency(1000, 'IDR') }}</span>
        <span>{{ Number::currency(500000, 'IDR') }}</span>
      </div>
    </div>
  </div>

</div>


        <!-- CONTENT -->
        <div class="p-4 flex flex-col flex-1">

          <!-- Sort -->
          <div class="mb-5">
            <div class="flex items-center justify-between px-4 py-3 bg-white border rounded-xl shadow-sm">
              <select wire:model.live="sort" class="w-44 text-base bg-white border border-gray-300 rounded-lg text-gray-700 focus:ring-rose-400">
                <option value="latest">Sort by latest</option>
                <option value="price">Sort by price</option>
              </select>
            </div>
          </div>

          <!-- Products -->
<div class="flex flex-wrap -mx-3">
  @foreach ($products as $product)
    <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3 lg:w-1/4" wire:key="{{ $product->id }}">

      <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden h-full flex flex-col">

        <!-- Image -->
        <div class="relative">
          <a href="/products/{{ $product->slug }}">
            <img
              src="{{ url('storage', $product->images[0]) }}"
              alt="{{ $product->name }}"
              class="w-full h-48 object-cover"
            >
          </a>

          <!-- Wishlist -->
          <button class="absolute top-3 right-3 bg-white rounded-full p-2 shadow text-gray-400 hover:text-rose-400 transition">
  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748z"/>
  </svg>
</button>

        </div>

        <!-- Content -->
        <div class="p-4 space-y-1">

          <!-- Product Name -->
         <h3 class="text-sm font-medium text-gray-800 line-clamp-2 min-h-[40px]">
  {{ $product->name }}
</h3>


          <!-- Old Price -->
          @if($product->old_price ?? false)
            <div class="text-xs text-gray-400 line-through">
              Rp {{ number_format($product->old_price, 0, ',', '.') }}
            </div>
          @endif

          <!-- Price + Discount -->
          <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-green-700">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>

            @if($product->old_price ?? false)
              <span class="text-xs text-green-700 border border-dashed border-green-600 px-2 py-0.5 rounded">
                {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
              </span>
            @endif
          </div>

          <!-- Cashback -->
          <div class="flex items-center gap-1 text-xs text-green-600">
            💰 Cashback
            <span class="font-semibold">
              Rp {{ number_format($product->cashback ?? 2450, 0, ',', '.') }}
            </span>
          </div>

          <!-- Rating -->
          <div class="flex items-center text-xs text-gray-500 gap-1">
            ⭐ {{ $product->rating ?? 5 }}
            <span>({{ $product->reviews_count ?? 54 }})</span>
            <span class="mx-1">•</span>
            <span>{{ $product->sold ?? '13,8rb' }} terjual</span>
          </div>

          <!-- Button -->
          <button
            wire:click.prevent="addToCart({{ $product->id }})"
            wire:loading.attr="disabled"
            wire:target="addToCart({{ $product->id }})"
            class="w-full mt-3 flex items-center justify-center gap-2 py-2 rounded-lg bg-[#7C8B5A] text-white font-semibold hover:opacity-90">

            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
              + Keranjang
            </span>

            <span wire:loading wire:target="addToCart({{ $product->id }})">
              Menambahkan...
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
