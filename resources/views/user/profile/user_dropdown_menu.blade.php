{{-- User Dropdown Menu Component --}}
{{-- Letakkan di navbar/header Anda --}}

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    {{-- Trigger Button --}}
    <button 
    @click.stop="open = !open" 
            class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150">
        
        {{-- Avatar --}}
        @if(auth()->user()->avatar)
            <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                 alt="{{ auth()->user()->name }}" 
                 class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
        @else
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center border-2 border-gray-200">
                <span class="text-sm font-bold text-white">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </span>
            </div>
        @endif

        {{-- User Name --}}
        <span class="font-medium text-sm hidden md:block">{{ auth()->user()->name }}</span>
        
        {{-- Arrow Icon --}}
        <svg class="w-4 h-4 transition-transform" 
             :class="{ 'rotate-180': open }"
             fill="none" 
             stroke="currentColor" 
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
        
        {{-- User Info Header --}}
        <div class="px-4 py-3 border-b border-gray-200">
            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
        </div>

        {{-- Menu Items --}}
        <div class="py-1">
            <a href="{{ route('user.profile.index') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Akun Saya
            </a>

            <a href="{{ route('user.orders.index') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Pesanan Saya
            </a>

            <a href="{{ route('user.addresses.index') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                Alamat Saya
            </a>
        </div>

        <div class="border-t border-gray-200 py-1">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

    </div>
</div>

{{-- Note: Membutuhkan Alpine.js untuk dropdown interaktif --}}
{{-- Tambahkan di layout: <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}