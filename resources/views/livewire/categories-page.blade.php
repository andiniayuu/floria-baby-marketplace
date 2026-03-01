<div class="pt-20 pb-20 min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="text-center mb-12">
      <h1 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">
        Belanja Berdasarkan Kategori
      </h1>
      <p class="text-lg text-slate-600 max-w-2xl mx-auto">
        Jelajahi koleksi produk bayi pilihan kami
      </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach ($categories as $category)
        <a href="/products?selected_categories[0]={{ $category->id }}" 
           wire:key="{{ $category->id }}"
           wire:navigate
           class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100 hover:border-blue-300 transform hover:-translate-y-2">
          
          <!-- Background Gradient Overlay -->
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-purple-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          
          <!-- Content -->
          <div class="relative p-6">
            <div class="flex items-center gap-4">
              
              <!-- Image Container -->
              <div class="relative flex-shrink-0">
                <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-blue-100 to-purple-100 p-3 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                  <img class="w-full h-full object-contain" 
                       src="{{ url('storage', $category->image) }}" 
                       alt="{{ $category->name }}">
                </div>
                <!-- Badge -->
                <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                  Baru
                </div>
              </div>
              
              <!-- Text Content -->
              <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-300 mb-1">
                  {{ $category->name }}
                </h3>
                <p class="text-sm text-slate-500 group-hover:text-slate-600 transition-colors">
                  Lihat koleksi
                </p>
              </div>
              
              <!-- Arrow Icon -->
              <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-slate-100 group-hover:bg-blue-500 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                  <svg class="w-5 h-5 text-slate-600 group-hover:text-white transition-colors duration-300 group-hover:translate-x-1" 
                       fill="none" 
                       stroke="currentColor" 
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </div>
              
            </div>
          </div>
          
          <!-- Bottom Accent Line -->
          <div class="h-1 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </a>
      @endforeach
    </div>

    <!-- Empty State -->
    @if(count($categories) === 0)
    <div class="text-center py-16">
      <svg class="w-24 h-24 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
      </svg>
      <h3 class="text-xl font-semibold text-slate-700 mb-2">Kategori Belum Tersedia</h3>
      <p class="text-slate-500">Kategori akan muncul di sini setelah ditambahkan.</p>
    </div>
    @endif

    <!-- CTA Section -->
    <div class="mt-16 text-center bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
      <h2 class="text-2xl font-bold text-slate-800 mb-3">
        Tidak menemukan yang Anda cari?
      </h2>
      <p class="text-slate-600 mb-6 max-w-xl mx-auto">
        Jelajahi semua produk kami atau hubungi tim layanan pelanggan untuk bantuan.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('products') }}" 
           wire:navigate
           class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Lihat Semua Produk
        </a>
        <a href="{{ route('home') }}" 
           wire:navigate
           class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold px-8 py-3 rounded-xl border-2 border-slate-200 hover:border-slate-300 transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          Kembali ke Beranda
        </a>
      </div>
    </div>

  </div>
</div>