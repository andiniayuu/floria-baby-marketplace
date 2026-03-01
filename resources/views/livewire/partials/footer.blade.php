<footer class="bg-gradient-to-br from-pink-50 via-white to-blue-50 border-t border-pink-100">
  <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Main Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

      <!-- Brand -->
      <div>
        <h3 class="text-4xl font-bold bg-gradient-to-r from-[#F8A1C4] to-[#7EC8E3] bg-clip-text text-transparent"
            style="font-family: 'Dancing Script', cursive;">
          Floria Baby
        </h3>
        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
          Produk bayi berkualitas tinggi yang aman, lembut,
          dan terpercaya untuk tumbuh kembang si kecil.
        </p>
      </div>

      <!-- Product -->
      <div>
        <h4 class="font-semibold text-gray-900 mb-4">Produk</h4>
        <ul class="space-y-3 text-sm text-gray-600">
          <li><a href="/categories" class="hover:text-pink-500 transition">Kategori</a></li>
          <li><a href="/products" class="hover:text-pink-500 transition">Semua Produk</a></li>
          <li><a href="/products?featured=1" class="hover:text-pink-500 transition">Produk Unggulan</a></li>
        </ul>
      </div>

      <!-- Company -->
      <div>
        <h4 class="font-semibold text-gray-900 mb-4">Perusahaan</h4>
        <ul class="space-y-3 text-sm text-gray-600">
          <li><a href="#" class="hover:text-pink-500 transition">Tentang Kami</a></li>
          <li><a href="#" class="hover:text-pink-500 transition">Blog</a></li>
          <li><a href="#" class="hover:text-pink-500 transition">Hubungi Kami</a></li>
        </ul>
      </div>

      <!-- Subscribe -->
      <div>
        <h4 class="font-semibold text-gray-900 mb-4">Dapatkan Update</h4>
        <p class="text-sm text-gray-600 mb-4">
          Promo & produk terbaru langsung ke email kamu.
        </p>

        <div class="flex items-center border border-pink-200 rounded-full overflow-hidden bg-white">
          <input
            type="email"
            placeholder="Alamat email"
            class="px-4 py-2.5 text-sm w-full focus:outline-none"
          />
          <button class="bg-gradient-to-r from-pink-500 to-blue-500 hover:shadow-lg transition text-white text-sm px-5 py-2.5 font-semibold whitespace-nowrap">
            Subscribe
          </button>
        </div>
      </div>

    </div>

    <!-- Trust & Payment -->
    <div class="mt-14 pt-10 border-t border-pink-100 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

      <!-- Trust Badge -->
      <div class="flex flex-wrap gap-3 text-sm text-gray-700">
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-pink-200 shadow-sm">
          <span class="text-pink-500">✓</span> 100% Original
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-blue-200 shadow-sm">
          <span class="text-blue-500">🚚</span> Pengiriman Cepat
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-pink-200 shadow-sm">
          <span class="text-pink-500">🔒</span> Pembayaran Aman
        </div>
      </div>

     {{-- Payment Icons --}}

      <div class="flex md:justify-end gap-3 items-center flex-wrap">
      
        {{-- VISA --}}
        <svg title="Visa" viewBox="0 0 50 32" width="50" height="32" xmlns="http://www.w3.org/2000/svg" class="opacity-70 hover:opacity-100 transition cursor-default">
          <rect width="50" height="32" rx="4" fill="white" stroke="#e5e7eb" stroke-width="1"/>
          <text x="25" y="21" font-family="Arial, sans-serif" font-size="13" font-weight="900" font-style="italic"
            fill="#1A1F71" text-anchor="middle" letter-spacing="-0.5">VISA</text>
        </svg>
      
        {{-- MASTERCARD --}}
        <svg title="Mastercard" viewBox="0 0 50 32" width="50" height="32" xmlns="http://www.w3.org/2000/svg" class="opacity-70 hover:opacity-100 transition cursor-default">
          <rect width="50" height="32" rx="4" fill="white" stroke="#e5e7eb" stroke-width="1"/>
          <circle cx="19" cy="16" r="9" fill="#EB001B"/>
          <circle cx="31" cy="16" r="9" fill="#F79E1B"/>
          <path d="M25 8.8A8.98 8.98 0 0 1 28.2 16 8.98 8.98 0 0 1 25 23.2 8.98 8.98 0 0 1 21.8 16 8.98 8.98 0 0 1 25 8.8Z" fill="#FF5F00"/>
        </svg>
      
        {{-- BCA --}}
        <svg title="BCA" viewBox="0 0 50 32" width="50" height="32" xmlns="http://www.w3.org/2000/svg" class="opacity-70 hover:opacity-100 transition cursor-default">
          <rect width="50" height="32" rx="4" fill="white" stroke="#e5e7eb" stroke-width="1"/>
          <text x="25" y="21" font-family="Arial, sans-serif" font-size="12" font-weight="900"
            fill="#005BAA" text-anchor="middle" letter-spacing="2">BCA</text>
        </svg>
      
        {{-- DANA --}}
        <svg title="DANA" viewBox="0 0 50 32" width="50" height="32" xmlns="http://www.w3.org/2000/svg" class="opacity-70 hover:opacity-100 transition cursor-default">
          <rect width="50" height="32" rx="4" fill="white" stroke="#e5e7eb" stroke-width="1"/>
          <text x="25" y="21" font-family="Arial, sans-serif" font-size="11" font-weight="900"
            fill="#118EEA" text-anchor="middle" letter-spacing="1.5">DANA</text>
        </svg>
      
        {{-- OVO --}}
        <svg title="OVO" viewBox="0 0 50 32" width="50" height="32" xmlns="http://www.w3.org/2000/svg" class="opacity-70 hover:opacity-100 transition cursor-default">
          <rect width="50" height="32" rx="4" fill="white" stroke="#e5e7eb" stroke-width="1"/>
          <text x="25" y="21" font-family="Arial, sans-serif" font-size="12" font-weight="900"
            fill="#4C3494" text-anchor="middle" letter-spacing="2">OVO</text>
        </svg>
      
      </div>

    </div>

    <!-- Bottom -->
    <div class="mt-10 pt-6 border-t border-pink-100 text-center text-sm text-gray-500">
      © 2025 Floria Baby. Seluruh hak cipta dilindungi.
    </div>
  </div>
</footer>