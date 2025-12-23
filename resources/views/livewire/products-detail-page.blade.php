<div class="w-full max-w-[85rem] mx-auto py-10 px-4 sm:px-6 lg:px-8">
  <section class="overflow-hidden bg-white py-11 font-poppins">
    <div class="max-w-6xl mx-auto px-4 py-4 md:px-6 lg:py-8">

      <div class="flex flex-wrap -mx-4">

        <!-- IMAGE SECTION -->
        <div
          class="w-full mb-8 md:w-1/2 md:mb-0"
          x-data="{ mainImage: '{{ url('storage', $product->images[0]) }}' }"
        >
          <div class="sticky top-24 z-30 overflow-hidden">

            <!-- MAIN IMAGE -->
            <div class="relative mb-6 lg:mb-10 bg-gray-50 border border-gray-200 rounded-2xl shadow-sm p-6">

              <!-- LEFT ARROW -->
              <button
                type="button"
                x-on:click="
                  let imgs = {{ json_encode($product->images) }};
                  let idx = imgs.findIndex(i => mainImage.includes(i));
                  if (idx > 0) mainImage = '{{ url('storage') }}/' + imgs[idx - 1];
                "
                class="absolute left-3 top-1/2 -translate-y-1/2
                       w-9 h-9 flex items-center justify-center
                       bg-white border border-gray-300 rounded-full shadow
                       hover:bg-gray-100"
              >
                ‹
              </button>

              <!-- RIGHT ARROW -->
              <button
                type="button"
                x-on:click="
                  let imgs = {{ json_encode($product->images) }};
                  let idx = imgs.findIndex(i => mainImage.includes(i));
                  if (idx < imgs.length - 1) mainImage = '{{ url('storage') }}/' + imgs[idx + 1];
                "
                class="absolute right-3 top-1/2 -translate-y-1/2
                       w-9 h-9 flex items-center justify-center
                       bg-white border border-gray-300 rounded-full shadow
                       hover:bg-gray-100"
              >
                ›
              </button>

              <img
                x-bind:src="mainImage"
                alt="{{ $product->name }}"
                class="w-full object-cover rounded-xl"
              >
            </div>

            <!-- THUMBNAILS (SCROLL ONLY) -->
            <div class="mt-4 overflow-x-auto">
              <div class="flex gap-3">
                @foreach ($product->images as $image)
                  <div
                    class="flex-shrink-0 w-1/4 cursor-pointer"
                    x-on:click="mainImage='{{ url('storage', $image) }}'"
                  >
                    <div
                      class="aspect-square bg-white border-2 rounded-xl p-2 transition"
                      :class="mainImage === '{{ url('storage', $image) }}'
                        ? 'border-rose-500'
                        : 'border-gray-200'"
                    >
                      <img
                        src="{{ url('storage', $image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain"
                      >
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- FREE SHIPPING -->
            <div class="px-6 pb-6 mt-6 border-t border-gray-300">
              <div class="flex items-center mt-6">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4 mr-2 text-gray-700"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7z"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-700">Free Shipping</h2>
              </div>
            </div>

          </div>
        </div>

        <!-- PRODUCT INFO -->
        <div class="w-full px-4 md:w-1/2">
          <div class="lg:pl-20">

            <div class="mb-8">
              <h2 class="max-w-xl mb-4 text-xl font-bold text-gray-900 sm:text-2xl md:text-4xl">
                {{ $product->name }}
              </h2>

              <p class="mb-4 text-2xl font-bold text-green-600 sm:text-3xl md:text-4xl">
                Rp{{ number_format($product->price, 0, ',', '.') }}
              </p>

              <p class="max-w-md text-sm leading-relaxed text-gray-600 sm:text-base">
                {{ $product->description }}
              </p>
            </div>

            <!-- QUANTITY -->
            <div class="mb-8">
              <label class="block pb-1 text-xl font-semibold text-gray-700 border-b border-gray-300">
                Quantity
              </label>

              <div class="flex w-full max-w-[180px] h-10 mt-4">
                <button
                  wire:click="decreaseQty"
                  class="w-20 flex items-center justify-center bg-gray-200 border border-gray-300 rounded-l hover:bg-gray-300 transition"
                >
                  <span class="text-2xl font-thin">-</span>
                </button>

                <input
                  type="number"
                  min="1"
                  wire:model.live="quantity"
                  class="w-full text-center font-semibold text-gray-800 bg-gray-200 border-t border-b border-gray-300 focus:outline-none"
                >

                <button
                  wire:click="increaseQty"
                  class="w-20 flex items-center justify-center bg-gray-200 border border-gray-300 rounded-r hover:bg-gray-300 transition"
                >
                  <span class="text-2xl font-thin">+</span>
                </button>
              </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col gap-3 sm:flex-row sm:gap-4 mt-6">

              <!-- ADD TO CART -->
              <button
                wire:click="addToCart({{ $product->id }})"
                wire:loading.attr="disabled"
                wire:target="addToCart({{ $product->id }})"
                class="flex items-center justify-center gap-3 w-full sm:w-1/2 px-6 py-4
                       text-base font-semibold text-white bg-rose-500 rounded-xl
                       hover:bg-rose-600 transition disabled:opacity-60"
              >
                <svg
                  wire:loading.remove
                  wire:target="addToCart({{ $product->id }})"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-5 h-5"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5z"/>
                </svg>

                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                  Add to Cart
                </span>

                <span wire:loading wire:target="addToCart({{ $product->id }})">
                  Adding...
                </span>
              </button>

              <!-- BELI SEKARANG (COMING SOON) -->
              <button
                type="button"
                disabled
                class="flex items-center justify-center w-full sm:w-1/2 px-6 py-4
                       text-base font-semibold text-rose-400 bg-rose-50
                       rounded-xl cursor-not-allowed"
              >
                Beli Sekarang
              </button>

            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
</div>
