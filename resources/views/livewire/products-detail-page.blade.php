<div class="w-full min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 pt-14">
  <div class="max-w-7xl mx-auto px-4 py-6">
    
    <!-- Breadcrumb -->
    <nav class="mb-4">
      <ol class="flex items-center gap-2 text-sm">
        <li><a href="/" class="text-pink-500 hover:text-pink-600">Beranda</a></li>
        <li class="text-gray-400">/</li>
        <li><a href="/products" class="text-pink-500 hover:text-pink-600">Produk</a></li>
        <li class="text-gray-400">/</li>
        <li class="text-gray-700">{{ $product->name }}</li>
      </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <!-- LEFT: Image Gallery -->
      <div class="lg:col-span-5">
        <div class="bg-white rounded-lg shadow-sm p-4" x-data="{ 
          mainImage: '{{ url('storage', $product->images[0]) }}',
          images: {{ json_encode(array_map(function($img) { return url('storage', $img); }, $product->images)) }},
          goToNext() {
            const currentIdx = this.images.findIndex(img => this.mainImage === img);
            if (currentIdx < this.images.length - 1) {
              this.mainImage = this.images[currentIdx + 1];
            }
          },
          goToPrev() {
            const currentIdx = this.images.findIndex(img => this.mainImage === img);
            if (currentIdx > 0) {
              this.mainImage = this.images[currentIdx - 1];
            }
          }
        }">
          
          <!-- Main Image with Stock Overlay -->
          <div class="relative mb-4 aspect-square bg-gray-50 rounded-lg overflow-hidden group">
            
            @if($product->stock <= 0)
              <div class="absolute inset-0 bg-black/50 z-10 flex items-center justify-center">
                <div class="bg-white/90 backdrop-blur-sm px-8 py-3 rounded-full">
                  <span class="text-red-600 font-bold text-lg">STOK HABIS</span>
                </div>
              </div>
            @endif

            <!-- Navigation Arrows -->
            @if(count($product->images) > 1)
              <button
                type="button"
                @click="goToPrev()"
                class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100"
              >
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
              </button>

              <button
                type="button"
                @click="goToNext()"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition opacity-0 group-hover:opacity-100"
              >
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            @endif

            <img
              x-bind:src="mainImage"
              alt="{{ $product->name }}"
              class="w-full h-full object-contain p-6"
            >
          </div>

          <!-- Thumbnails -->
          @if(count($product->images) > 1)
            <div class="flex gap-2 overflow-x-auto pb-2">
              @foreach ($product->images as $image)
                <button
                  type="button"
                  class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all hover:border-pink-400"
                  :class="mainImage === '{{ url('storage', $image) }}' ? 'border-pink-500' : 'border-gray-200'"
                  @click="mainImage='{{ url('storage', $image) }}'"
                >
                  <img
                    src="{{ url('storage', $image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-contain p-1"
                  >
                </button>
              @endforeach
            </div>
          @endif

          <!-- Share & Favorite -->
          <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
            <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-pink-500 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
              </svg>
              <span>Bagikan</span>
            </button>
            <button class="flex items-center gap-2 text-sm text-gray-600 hover:text-red-500 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              <span>Favorit</span>
            </button>
          </div>
        </div>
      </div>

      <!-- RIGHT: Product Info -->
      <div class="lg:col-span-7">
        <div class="bg-white rounded-lg shadow-sm p-6">
          
          <!-- Discount Badge -->
          @if($product->old_price ?? false)
            <div class="inline-flex items-center gap-2 mb-3">
              <span class="bg-pink-500 text-white px-2 py-0.5 rounded text-xs font-bold">
                Mall
              </span>
              <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold">
                {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}% OFF
              </span>
            </div>
          @endif

          <!-- Product Title -->
          <h1 class="text-xl font-medium text-gray-900 mb-4 leading-relaxed">
            {{ $product->name }}
          </h1>
          
          <!-- Rating & Sales -->
          <div class="flex items-center gap-6 mb-6 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-1.5">
              <span class="text-pink-500 text-base">{{ $product->rating ?? 4.9 }}</span>
              <div class="flex gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                  <svg class="w-4 h-4 {{ $i <= ($product->rating ?? 5) ? 'text-pink-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                @endfor
              </div>
            </div>
            
            <div class="h-4 w-px bg-gray-300"></div>
            
            <div class="flex items-center gap-1.5">
              <span class="text-gray-900 font-medium">{{ $product->reviews_count ?? 1248 }}</span>
              <span class="text-gray-500 text-sm">Penilaian</span>
            </div>
            
            <div class="h-4 w-px bg-gray-300"></div>
            
            <div class="flex items-center gap-1.5">
              <span class="text-gray-900 font-medium">{{ $product->sold ?? '5,2rb' }}</span>
              <span class="text-gray-500 text-sm">Terjual</span>
            </div>
          </div>

          <!-- Price Section -->
          <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg p-4 mb-6">
            @if($product->old_price ?? false)
              <div class="flex items-center gap-3 mb-1">
                <span class="text-gray-400 text-base line-through">
                  Rp{{ number_format($product->old_price, 0, ',', '.') }}
                </span>
              </div>
            @endif
            
            <div class="flex items-baseline gap-2">
              <span class="text-pink-600 text-3xl font-bold">
                Rp{{ number_format($product->price, 0, ',', '.') }}
              </span>
            </div>

            @if($product->cashback ?? false)
              <div class="mt-3 inline-flex items-center gap-2 bg-green-50 px-3 py-1.5 rounded-full">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-700 text-sm font-semibold">
                  Cashback Rp{{ number_format($product->cashback ?? 2450, 0, ',', '.') }}
                </span>
              </div>
            @endif
          </div>

          <!-- Shipping Info -->
          <div class="mb-6 pb-6 border-b border-gray-100">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
              </svg>
              <div>
                <p class="text-gray-900 font-medium text-sm mb-1">Pengiriman</p>
                <div class="flex items-center gap-2">
                  <span class="text-gray-600 text-sm">Gratis Ongkir</span>
                  <span class="text-gray-400">•</span>
                  <span class="text-gray-600 text-sm">Khusus area Jawa</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Stock -->
          <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-gray-700 text-sm">Stok</span>
              @if($product->stock > 0)
                <span class="text-gray-900 font-medium">{{ $product->stock }} tersisa</span>
              @else
                <span class="text-red-500 font-medium">Habis</span>
              @endif
            </div>
            
            @if($product->stock > 0 && $product->stock <= 10)
              <div class="bg-pink-50 border border-pink-200 rounded-lg px-3 py-2">
                <p class="text-pink-700 text-xs">⚠️ Stok terbatas! Segera checkout sebelum kehabisan</p>
              </div>
            @endif
          </div>

          <!-- Quantity -->
          @if($product->stock > 0)
           <div class="mb-8">
    <label class="block text-gray-700 text-sm mb-3">Kuantitas</label>
    
    <div class="flex items-center gap-3">
        <button
            type="button"
            wire:click="decreaseQty"
            {{ $quantity <= 1 ? 'disabled' : '' }}
            class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 
                   hover:bg-pink-200 disabled:opacity-40"
        >
            -
        </button>

        <div class="min-w-[64px] text-center">
            <span class="text-lg font-bold">{{ $quantity }}</span>
        </div>

        <button
            type="button"
            wire:click="increaseQty"
            {{ $quantity >= $product->stock ? 'disabled' : '' }}
            class="w-10 h-10 rounded-full bg-pink-500 text-white 
                   hover:bg-pink-600 disabled:opacity-40"
        >
            +
        </button>
    </div>
</div>
          @else
            <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-4">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="text-red-700 text-sm font-medium">Produk ini sedang habis stok</span>
              </div>
            </div>
          @endif

          <!-- Action Buttons -->
          <div class="flex gap-3">
            @if($product->stock > 0)
              <button
                wire:click="addToCart({{ $product->id }})"
                wire:loading.attr="disabled"
                wire:target="addToCart({{ $product->id }})"
                class="flex-1 flex items-center justify-center gap-2 px-6 py-3 text-pink-600 bg-pink-50 border border-pink-500 rounded-md hover:bg-pink-100 transition font-medium disabled:opacity-50"
              >
                <svg wire:loading.remove wire:target="addToCart({{ $product->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Tambah ke Keranjang</span>
                <span wire:loading wire:target="addToCart({{ $product->id }})" class="flex items-center gap-2">
                  <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                  </svg>
                  Menambahkan...
                </span>
              </button>

              <button
                wire:click="buyNow"
                wire:loading.attr="disabled"
                class="flex-1 flex items-center justify-center gap-2 px-6 py-3 text-white bg-pink-500 rounded-md hover:bg-pink-600 transition font-medium disabled:opacity-50"
              >
                Beli Sekarang
              </button>
            @else
              <button disabled class="w-full px-6 py-3 text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed font-medium">
                Stok Habis
              </button>
            @endif
          </div>

        </div>
      </div>
    </div>

    <!-- Product Description -->
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Detail Produk</h2>
      <div class="prose max-w-none text-gray-600 text-sm leading-relaxed">
        {!! $product->description !!}
      </div>
    </div>

  </div>
</div>