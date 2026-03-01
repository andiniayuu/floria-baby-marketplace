<div class="w-full min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50">
  <div class="max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 font-poppins">
      <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
        <div class="flex flex-wrap mb-24 -mx-3">

          <!-- SIDEBAR -->
          <div class="w-full pr-2 lg:w-1/4 space-y-6">

            <!-- Categories -->
            <div class="bg-white rounded-2xl shadow-md p-5">
              <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Kategori</h2>
              </div>
              <ul class="space-y-3">
                @foreach ($categories as $category)
                  <li wire:key="{{ $category->id }}">
                    <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer transition group">
                      <input
                        type="checkbox"
                        wire:model.live="selected_categories"
                        value="{{ $category->id }}"
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                      <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                    </label>
                  </li>
                @endforeach
              </ul>
            </div>

            <!-- Brand -->
            <div class="bg-white rounded-2xl shadow-md p-5">
              <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Brand</h2>
              </div>
              <ul class="space-y-3">
                @foreach ($brands as $brand)
                  <li wire:key="{{ $brand->id }}">
                    <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer transition group">
                      <input
                        type="checkbox"
                        wire:model.live="selected_brands"
                        value="{{ $brand->id }}"
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                      <span class="text-sm font-medium text-gray-700">{{ $brand->name }}</span>
                    </label>
                  </li>
                @endforeach
              </ul>
            </div>

            <!-- Product Status -->
            <div class="bg-white rounded-2xl shadow-md p-5">
              <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Status Produk</h2>
              </div>
              <ul class="space-y-3">
                <li>
                  <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                    <input
                      type="checkbox"
                      wire:model.live="featured"
                      class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="text-sm font-medium text-gray-700">Produk Unggulan</span>
                  </label>
                </li>
                <li>
                  <label class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                    <input
                      type="checkbox"
                      wire:model.live="on_sale"
                      class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="text-sm font-medium text-gray-700">Promo Diskon</span>
                  </label>
                </li>
              </ul>
            </div>

            <!-- Price -->
            <div class="bg-white rounded-2xl shadow-md p-5">
              <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Harga</h2>
              </div>
              <div>
                <div class="mb-3 text-2xl font-bold text-gray-800">
                  {{ Number::currency($price_range, 'IDR') }}
                </div>
                <input
                  type="range"
                  wire:model.live="price_range"
                  max="500000"
                  step="1000"
                  class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                <div class="flex justify-between text-xs font-semibold text-gray-500 mt-2">
                  <span>{{ Number::currency(1000, 'IDR') }}</span>
                  <span>{{ Number::currency(500000, 'IDR') }}</span>
                </div>
              </div>
            </div>

          </div>

          <!-- CONTENT -->
          <div class="p-4 flex flex-col flex-1">

            <!-- Sort & Total -->
            <div class="mb-6">
              <div class="flex items-center justify-between px-6 py-4 bg-white rounded-2xl shadow-md">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                  </svg>
                  <select wire:model.live="sort" class="text-base bg-white border-2 border-gray-200 rounded-xl text-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest">🆕 Terbaru</option>
                    <option value="price">💰 Harga</option>
                  </select>
                </div>
                <span class="text-sm text-gray-500 font-medium">{{ $totalCount }} produk</span>
              </div>
            </div>

            @php
              $inStock    = $usePagination ? $products->getCollection()->where('stock', '>', 0) : $products->where('stock', '>', 0);
              $outOfStock = $usePagination ? $products->getCollection()->where('stock', '<=', 0) : $products->where('stock', '<=', 0);
            @endphp

            <!-- In Stock Products -->
            @if($inStock->count() > 0)
              <div class="flex flex-wrap -mx-3">
                @foreach ($inStock as $product)
                  <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3 lg:w-1/4" wire:key="in-{{ $product->id }}">
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden h-full flex flex-col group">

                      <!-- Image -->
                      <div class="relative overflow-hidden">
                        <a href="/products/{{ $product->slug }}" class="block">
                          <img
                            src="{{ url('storage', $product->images[0]) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                        @if($product->compare_price && $product->compare_price > $product->price)
                          <div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                            -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                          </div>
                        @endif
                        @if($product->is_featured)
                          <div class="absolute top-3 right-3 bg-yellow-400 text-white px-2 py-1 rounded-full text-xs font-bold shadow">
                            ⭐ Unggulan
                          </div>
                        @endif
                      </div>

                      <!-- Content -->
                      <div class="p-5 space-y-3 flex-1 flex flex-col">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[40px]">
                          {{ $product->name }}
                        </h3>
                        @if($product->compare_price && $product->compare_price > $product->price)
                          <div class="text-xs text-gray-400 line-through">
                            Rp {{ number_format($product->compare_price, 0, ',', '.') }}
                          </div>
                        @endif
                        <div class="flex items-center gap-2 flex-wrap">
                          <span class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                          </span>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-green-600">
                          💰 Cashback
                          <span class="font-semibold">Rp {{ number_format($product->cashback ?? 2450, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1">
                          ⭐ {{ $product->rating ?? 5 }}
                          <span>({{ $product->reviews_count ?? 54 }})</span>
                          <span class="mx-1">•</span>
                          <span>{{ $product->sold ?? '13,8rb' }} terjual</span>
                        </div>
                        <div class="mt-auto pt-2">
                          <button
                            wire:click="addToCart({{ $product->id }})"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors duration-200">
                            + Keranjang
                          </button>
                        </div>
                      </div>

                    </div>
                  </div>
                @endforeach
              </div>
            @endif

            <!-- Out of Stock Separator + Products -->
            @if($outOfStock->count() > 0)
              <div class="my-12 relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t-2 border-gray-300"></div>
                </div>
                <div class="relative flex justify-center">
                  <span class="px-6 py-2 bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 text-gray-600 font-semibold text-sm rounded-full border-2 border-gray-300 shadow-sm">
                    Produk Habis
                  </span>
                </div>
              </div>

              <div class="flex flex-wrap -mx-3">
                @foreach ($outOfStock as $product)
                  <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3 lg:w-1/4" wire:key="out-{{ $product->id }}">
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden h-full flex flex-col opacity-70">

                      <!-- Image -->
                      <div class="relative overflow-hidden">
                        <a href="/products/{{ $product->slug }}" class="block">
                          <img
                            src="{{ url('storage', $product->images[0]) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-56 object-cover">
                        </a>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                          <div class="bg-black/70 text-white font-bold w-20 h-20 flex items-center justify-center rounded-full shadow-lg text-sm tracking-wide">
                            HABIS
                          </div>
                        </div>
                        @if($product->compare_price && $product->compare_price > $product->price)
                          <div class="absolute top-3 left-3 bg-red-400 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg opacity-70">
                            -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                          </div>
                        @endif
                      </div>

                      <!-- Content -->
                      <div class="p-5 space-y-3 flex-1 flex flex-col">
                        <h3 class="text-sm font-semibold text-gray-500 line-clamp-2 min-h-[40px]">
                          {{ $product->name }}
                        </h3>
                        @if($product->compare_price && $product->compare_price > $product->price)
                          <div class="text-xs text-gray-300 line-through">
                            Rp {{ number_format($product->compare_price, 0, ',', '.') }}
                          </div>
                        @endif
                        <div class="flex items-center gap-2 flex-wrap">
                          <span class="text-xl font-bold text-gray-400">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                          </span>
                        </div>
                        <div class="flex items-center text-xs text-gray-400 gap-1">
                          ⭐ {{ $product->rating ?? 5 }}
                          <span>({{ $product->reviews_count ?? 54 }})</span>
                          <span class="mx-1">•</span>
                          <span>{{ $product->sold ?? '13,8rb' }} terjual</span>
                        </div>
                      </div>

                    </div>
                  </div>
                @endforeach
              </div>
            @endif

            <!-- Empty State -->
            @if($inStock->count() === 0 && $outOfStock->count() === 0)
              <div class="flex flex-col items-center justify-center py-24 text-gray-400">
                <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-lg font-semibold">Tidak ada produk ditemukan</p>
                <p class="text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
              </div>
            @endif

            <!-- Pagination (hanya muncul jika > 20 produk) -->
            @if($usePagination)
              <div class="flex justify-end mt-8">
                {{ $products->links() }}
              </div>
            @endif

          </div>
        </div>
      </div>
    </section>
  </div>
</div>