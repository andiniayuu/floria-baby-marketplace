<x-layouts.app>

@section('title', 'Profil Saya')

@section('content')
<div class="pt-20 min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi profil Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            {{-- Sidebar Menu --}}
            <div class="lg:col-span-1">
                @include('user.profile.partials.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3 space-y-6">
                
                {{-- SELLER SECTION - HANYA SATU KONDISI --}}
                @if($user->role === 'seller')
                    {{-- User sudah menjadi seller - Tampilkan link ke dashboard --}}
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">✨ Dashboard Seller</h3>
                                <p class="text-green-100 mb-4">
                                    Kelola toko, produk, dan pesanan Anda dari dashboard seller.
                                </p>
                                <a href="{{ Filament\Facades\Filament::getPanel('seller')->getUrl() }}"
                                   class="inline-flex items-center px-6 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-green-50 transition duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    Buka Dashboard Seller
                                </a>
                            </div>
                            <svg class="hidden md:block w-24 h-24 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                
                @elseif(isset($sellerRequest) && $sellerRequest->status === 'pending')
                    {{-- Ada pengajuan yang sedang pending --}}
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-yellow-900 mb-1">⏳ Pengajuan Seller Sedang Diproses</h3>
                        <p class="text-sm text-yellow-800 mb-3">Pengajuan Anda sedang diverifikasi. Kami akan mengabari segera setelah disetujui.</p>
                        <a href="{{ route('user.seller.status') }}" class="text-sm text-yellow-900 hover:text-yellow-700 font-medium">
                            Lihat Status Pengajuan →
                        </a>
                    </div>
                
                @else
                    {{-- Belum pernah mengajukan atau pengajuan ditolak --}}
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                        <h3 class="text-xl font-bold mb-2">💼 Ingin Mulai Berjualan?</h3>
                        <p class="text-blue-100 mb-4">
                            Bergabunglah dengan ribuan seller lainnya dan mulai raih penghasilan dari toko online Anda!
                        </p>
                        <a href="{{ route('user.seller.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition duration-150">
                            Daftar Jadi Seller Sekarang
                        </a>
                    </div>
                @endif

                {{-- Profile Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-6">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center border-4 border-blue-100">
                                    <span class="text-3xl font-bold text-white">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- User Info --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                                @if($user->role === 'seller')
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                        ✓ Seller
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                            @if($user->phone)
                            <p class="text-gray-600 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $user->phone }}
                            </p>
                            @endif
                        </div>

                        {{-- Edit Button --}}
                        <div>
                            <a href="{{ route('user.profile.edit') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- Total Orders --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Pesanan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Total Addresses --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Alamat Tersimpan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalAddresses ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Member Since --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Member Sejak</p>
                                <p class="text-lg font-bold text-gray-900">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Recent Orders (if exists) --}}
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                        <a href="{{ route('user.my-orders') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($recentOrders as $order)
                        <a href="{{ route('user.my-orders.show', $order->id) }}"
                           class="block border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                           ($order->status === 'processing' ? 'bg-blue-100 text-blue-700' : 
                                           'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <a href="{{ route('user.addresses.index') }}" 
                           class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Alamat</span>
                        </a>

                        <a href="{{ route('user.profile.password') }}" 
                           class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Password</span>
                        </a>

                        <a href="{{ route('user.my-orders') }}" 
                           class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Pesanan</span>
                        </a>

                        @if($user->role !== 'seller' && (!isset($sellerRequest) || $sellerRequest->status !== 'pending'))
                            <a href="{{ route('user.seller.create') }}" 
                               class="flex flex-col items-center p-4 border-2 border-blue-300 bg-blue-50 rounded-lg hover:border-blue-400 hover:bg-blue-100 transition duration-150">
                                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="text-sm font-medium text-blue-700">Jadi Seller</span>
                            </a>
                        @else
                            <a href="{{ route('user.profile.edit') }}" 
                               class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Edit Profil</span>
                            </a>
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
</x-layouts.app>