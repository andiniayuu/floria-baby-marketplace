<header
  class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100"
  x-data="{ mobileMenu: false }"
  x-cloak
>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">

  <nav class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between h-16">

      <!-- LOGO -->
      <a href="/" class="inline-block">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-[#F8A1C4] to-[#7EC8E3]
                   bg-clip-text text-transparent"
            style="font-family: 'Dancing Script', cursive;">
          Floria Baby
        </h1>
      </a>

      <!-- RIGHT MENU (DESKTOP) -->
      <div class="hidden md:flex items-center gap-5">

        <a href="/"
           class="text-sm font-medium transition-colors hover:text-pink-500 relative group
                  {{ request()->is('/') ? 'text-pink-500' : 'text-gray-600' }}">
          Home
          <span class="absolute -bottom-1 left-0 h-0.5 bg-pink-500 transition-all duration-200
                       {{ request()->is('/') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
        </a>

        <a href="/categories"
           class="text-sm font-medium transition-colors hover:text-pink-500 relative group
                  {{ request()->is('categories*') ? 'text-pink-500' : 'text-gray-600' }}">
          Kategori
          <span class="absolute -bottom-1 left-0 h-0.5 bg-pink-500 transition-all duration-200
                       {{ request()->is('categories*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
        </a>

        <a href="/products"
           class="text-sm font-medium transition-colors hover:text-pink-500 relative group
                  {{ request()->is('products*') ? 'text-pink-500' : 'text-gray-600' }}">
          Produk
          <span class="absolute -bottom-1 left-0 h-0.5 bg-pink-500 transition-all duration-200
                       {{ request()->is('products*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
        </a>

        <!-- CART -->
        <a href="/cart"
           class="relative transition-colors hover:text-pink-500
                  {{ request()->is('cart*') ? 'text-pink-500' : 'text-gray-700' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>

          @if($total_count > 0)
            <span class="absolute -top-2 -right-2 w-5 h-5 bg-pink-500 text-white text-xs
                         rounded-full flex items-center justify-center">
              {{ $total_count }}
            </span>
          @endif
        </a>

        <!-- AUTH -->
        @guest
          <a href="/login"
             class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-blue-500 text-white
                    rounded-full font-semibold hover:shadow-lg transition hover:scale-105">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span>Masuk</span>
            </div>
          </a>
        @endguest

        @auth
        <div x-data="{ open: false }" class="relative">
          <button
            type="button"
            @click.stop="open = !open"
            class="flex items-center gap-2 text-gray-700 hover:text-pink-500 font-medium"
          >
            <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-blue-400 rounded-full
                        flex items-center justify-center text-white font-semibold">
              {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <span class="hidden lg:block">{{ auth()->user()->name }}</span>
            <svg class="w-4 h-4 transition-transform"
                 :class="{ 'rotate-180': open }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div
            x-show="open"
            @click.outside="open = false"
            style="display: none;"
            x-transition
            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl
                   border border-gray-100 py-2 z-[9999]">
            <div class="px-4 py-3 border-b border-gray-100">
              <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('user.profile.index') }}"
               class="block px-4 py-2.5 text-sm hover:bg-pink-50 hover:text-pink-600
                      {{ request()->routeIs('user.profile*') ? 'text-pink-600 bg-pink-50' : 'text-gray-700' }}">
              Akun Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                      class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                Keluar
              </button>
            </form>
          </div>
        </div>
        @endauth

      </div>

      <!-- MOBILE BUTTON -->
      <button
        x-on:click="mobileMenu = !mobileMenu"
        class="md:hidden p-2 text-gray-700 hover:text-pink-500"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
          <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </nav>

  <!-- MOBILE MENU -->
  <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-t border-gray-100">
    <div class="px-4 py-4 space-y-1">

      <a href="/"
         class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                {{ request()->is('/') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-500' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Home
      </a>

      <a href="/categories"
         class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                {{ request()->is('categories*') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-500' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        Kategori
      </a>

      <a href="/products"
         class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                {{ request()->is('products*') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-500' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        Produk
      </a>

      <a href="/cart"
         class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                {{ request()->is('cart*') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-500' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Keranjang
        @if(isset($total_count) && $total_count > 0)
          <span class="ml-auto w-5 h-5 bg-pink-500 text-white text-xs rounded-full flex items-center justify-center">
            {{ $total_count }}
          </span>
        @endif
      </a>

      @auth
        <div class="border-t border-gray-100 pt-3 mt-3">
          <a href="{{ route('user.profile.index') }}"
             class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('user.profile*') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-500' }}">
            <div class="w-6 h-6 bg-gradient-to-br from-pink-400 to-blue-400 rounded-full
                        flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
              {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
              <p class="font-semibold">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-400">Akun Saya</p>
            </div>
          </a>
          <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium
                           text-red-600 hover:bg-red-50 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              Keluar
            </button>
          </form>
        </div>
      @endauth

      @guest
        <div class="border-t border-gray-100 pt-3 mt-3">
          <a href="/login"
             class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r
                    from-pink-500 to-blue-500 text-white rounded-lg font-semibold
                    hover:shadow-lg transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Masuk
          </a>
        </div>
      @endguest

    </div>
  </div>
</header>