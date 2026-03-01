<x-layouts.app title="Checkout">

@section('content')
<div class="min-h-screen bg-gray-50 pt-24 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

        <form action="{{ route('user.checkout.process') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Pilih Alamat Section --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Alamat Pengiriman</h2>
                            <a href="{{ route('user.addresses.create') }}" 
                               target="_blank"
                               class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                + Tambah Alamat Baru
                            </a>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses as $address)
                                <label class="block cursor-pointer">
                                    <input type="radio" 
                                           name="address_id" 
                                           value="{{ $address->id }}" 
                                           {{ $address->is_primary ? 'checked' : '' }}
                                           class="peer hidden"
                                           required>
                                    
                                    <div class="border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 transition duration-150 hover:border-gray-300">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                {{-- Label & Badge --}}
                                                <div class="flex items-center gap-2 mb-2">
                                                    @if($address->label)
                                                    <span class="px-2 py-1 bg-gray-100 peer-checked:bg-blue-100 text-gray-700 peer-checked:text-blue-700 text-xs font-medium rounded">
                                                        {{ $address->label }}
                                                    </span>
                                                    @endif
                                                    
                                                    @if($address->is_primary)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                                        Utama
                                                    </span>
                                                    @endif
                                                </div>

                                                {{-- Recipient Info --}}
                                                <h3 class="font-semibold text-gray-900 mb-1">
                                                    {{ $address->recipient_name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mb-1">{{ $address->phone }}</p>

                                                {{-- Address --}}
                                                <p class="text-sm text-gray-700">
                                                    {{ $address->formatted_address }}
                                                </p>

                                                @if($address->notes)
                                                <p class="text-xs text-gray-500 italic mt-1">
                                                    {{ $address->notes }}
                                                </p>
                                                @endif
                                            </div>

                                            {{-- Check Icon --}}
                                            <div class="ml-4 hidden peer-checked:block">
                                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            @error('address_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-gray-600 mb-4">Anda belum memiliki alamat pengiriman</p>
                                <a href="{{ route('user.addresses.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                                    Tambah Alamat
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Metode Pengiriman --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pengiriman</h2>
                        
                        <div class="space-y-3">
                            <label class="block cursor-pointer">
                                <input type="radio" name="shipping_method" value="regular" checked class="peer hidden" required>
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 transition duration-150 hover:border-gray-300">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Reguler</h3>
                                            <p class="text-sm text-gray-600">Estimasi 3-5 hari kerja</p>
                                        </div>
                                        <span class="font-semibold text-gray-900">Rp 15.000</span>
                                    </div>
                                </div>
                            </label>

                            <label class="block cursor-pointer">
                                <input type="radio" name="shipping_method" value="express" class="peer hidden" required>
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 transition duration-150 hover:border-gray-300">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Express</h3>
                                            <p class="text-sm text-gray-600">Estimasi 1-2 hari kerja</p>
                                        </div>
                                        <span class="font-semibold text-gray-900">Rp 25.000</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('shipping_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>
                        
                        <div class="space-y-3">
                            <label class="block cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" checked class="peer hidden" required>
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 transition duration-150 hover:border-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Transfer Bank</h3>
                                            <p class="text-sm text-gray-600">BCA, BNI, Mandiri</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="block cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="peer hidden" required>
                                <div class="border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 transition duration-150 hover:border-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Cash on Delivery (COD)</h3>
                                            <p class="text-sm text-gray-600">Bayar saat barang sampai</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan Pesanan --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan Pesanan (Opsional)</h2>
                        <textarea name="notes" 
                                  rows="3" 
                                  placeholder="Tulis catatan untuk penjual (opsional)"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                </div>

                {{-- Summary Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-medium">Rp 500.000</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-medium">Rp 15.000</span>
                            </div>
                        </div>

                        <div class="flex justify-between mb-6">
                            <span class="text-base font-semibold text-gray-900">Total Pembayaran</span>
                            <span class="text-lg font-bold text-blue-600">Rp 515.000</span>
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                            Buat Pesanan
                        </button>

                        @if($addresses->count() == 0)
                        <p class="text-xs text-center text-red-600 mt-2">
                            Tambahkan alamat terlebih dahulu
                        </p>
                        @endif
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
</x-layouts.app>