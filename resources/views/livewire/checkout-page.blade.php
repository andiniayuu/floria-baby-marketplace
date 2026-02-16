<div class="min-h-screen bg-gray-50 pt-24 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

        {{-- Flash Messages --}}
        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-4">
                
                {{-- 📦 ORDER ITEMS PREVIEW --}}
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Order Items ({{ count($checkout_items) }})
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($checkout_items as $item)
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <img class="w-16 h-16 rounded-md object-cover border border-gray-200 flex-shrink-0"
                                 src="{{ url('storage', $item['image']) }}"
                                 alt="{{ $item['name'] }}">
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 text-sm line-clamp-2 mb-1">
                                    {{ $item['name'] }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {{ Number::currency($item['unit_amount'], 'IDR') }} × {{ $item['quantity'] }}
                                </p>
                            </div>
                            
                            <div class="text-right flex-shrink-0">
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ Number::currency($item['total_amount'], 'IDR') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- 📍 ALAMAT PENGIRIMAN --}}
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-base font-semibold text-gray-900">Alamat Pengiriman</h2>
                        <a href="{{ route('user.addresses.create') }}" 
                           target="_blank"
                           class="text-xs text-pink-600 hover:text-pink-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Alamat
                        </a>
                    </div>

                    @php
                        // Ambil hanya alamat utama
                        $primaryAddress = $addresses->firstWhere('is_primary', true);
                    @endphp

                    @if($primaryAddress)
                        {{-- Display Primary Address --}}
                        <div class="border-2 border-pink-500 bg-pink-50 rounded-sm p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    {{-- Label & Badge --}}
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 bg-pink-500 text-white text-xs font-semibold rounded-sm">
                                            Alamat Utama
                                        </span>
                                        @if($primaryAddress->label)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-sm">
                                            {{ $primaryAddress->label }}
                                        </span>
                                        @endif
                                    </div>

                                    {{-- Recipient Info --}}
                                    <h3 class="font-semibold text-gray-900 mb-1 text-sm">
                                        {{ $primaryAddress->recipient_name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-1">{{ $primaryAddress->phone }}</p>

                                    {{-- Address --}}
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $primaryAddress->formatted_address }}
                                    </p>

                                    @if($primaryAddress->notes)
                                    <p class="text-xs text-gray-500 italic mt-2 pt-2 border-t border-pink-200">
                                        Catatan: {{ $primaryAddress->notes }}
                                    </p>
                                    @endif
                                </div>

                                {{-- Check Icon --}}
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full bg-pink-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden input for Livewire --}}
                            <input type="hidden" wire:model="selected_address_id" value="{{ $primaryAddress->id }}">

                            {{-- Link untuk ubah alamat --}}
                            <div class="mt-3 pt-3 border-t border-pink-200">
                                <a href="{{ route('user.addresses.index') }}" 
                                   target="_blank"
                                   class="text-xs text-pink-600 hover:text-pink-700 font-medium">
                                    Pilih alamat lain atau kelola alamat →
                                </a>
                            </div>
                        </div>

                    @else
                        {{-- No Primary Address --}}
                        <div class="text-center py-8 bg-gray-50 rounded-sm border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-gray-600 font-medium mb-1">Belum Ada Alamat Utama</p>
                            <p class="text-sm text-gray-500 mb-4">Atur alamat utama untuk melanjutkan checkout</p>
                            <a href="{{ route('user.addresses.index') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white font-medium rounded-sm transition-all shadow-sm hover:shadow text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Kelola Alamat
                            </a>
                        </div>

                        @error('selected_address_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    @endif
                </div>

                {{-- 🚚 METODE PENGIRIMAN --}}
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Metode Pengiriman</h2>
                    
                    <div class="space-y-2">
                        {{-- Regular Shipping --}}
                        <label class="block cursor-pointer">
                            <input type="radio" 
                                   wire:model.live="shipping_method" 
                                   value="regular" 
                                   name="shipping_selection"
                                   class="hidden radio-input"
                                   id="shipping-regular">
                            
                            <div class="radio-option border-2 border-gray-200 rounded-sm p-4 hover:border-gray-300">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        {{-- Radio Bullet --}}
                                        <div class="radio-bullet flex-shrink-0">
                                            <div class="radio-outer"></div>
                                            <div class="radio-inner"></div>
                                        </div>
                                        
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">Reguler</h3>
                                            <p class="text-xs text-gray-600">Estimasi 3-5 hari kerja</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-gray-900 text-sm">{{ Number::currency(15000, 'IDR') }}</span>
                                </div>
                            </div>
                        </label>

                        {{-- Express Shipping --}}
                        <label class="block cursor-pointer">
                            <input type="radio" 
                                   wire:model.live="shipping_method" 
                                   value="express" 
                                   name="shipping_selection"
                                   class="hidden radio-input"
                                   id="shipping-express">
                            
                            <div class="radio-option border-2 border-gray-200 rounded-sm p-4 hover:border-gray-300">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        {{-- Radio Bullet --}}
                                        <div class="radio-bullet flex-shrink-0">
                                            <div class="radio-outer"></div>
                                            <div class="radio-inner"></div>
                                        </div>
                                        
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">Express</h3>
                                            <p class="text-xs text-gray-600">Estimasi 1-2 hari kerja</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-gray-900 text-sm">{{ Number::currency(25000, 'IDR') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('shipping_method')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- 💳 METODE PEMBAYARAN --}}
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>
                    
                    <div class="space-y-2">
                        {{-- Midtrans Payment Gateway --}}
                        <label class="block cursor-pointer">
                            <input type="radio" 
                                   wire:model.live="payment_method" 
                                   value="midtrans" 
                                   name="payment_selection"
                                   class="hidden radio-input"
                                   id="payment-midtrans">
                            
                            <div class="radio-option border-2 border-gray-200 rounded-sm p-4 hover:border-gray-300">
                                <div class="flex items-center gap-3">
                                    {{-- Radio Bullet --}}
                                    <div class="radio-bullet flex-shrink-0">
                                        <div class="radio-outer"></div>
                                        <div class="radio-inner"></div>
                                    </div>
                                    
                                    <svg class="w-6 h-6 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-sm">Transfer Bank / E-Wallet</h3>
                                        <p class="text-xs text-gray-600 mt-0.5">BCA, BNI, Mandiri, GoPay, OVO, QRIS, dll</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-sm">Midtrans</span>
                                            <span class="text-xs text-gray-500">Payment Gateway Aman</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- COD --}}
                        <label class="block cursor-pointer">
                            <input type="radio" 
                                   wire:model.live="payment_method" 
                                   value="cod" 
                                   name="payment_selection"
                                   class="hidden radio-input"
                                   id="payment-cod">
                            
                            <div class="radio-option border-2 border-gray-200 rounded-sm p-4 hover:border-gray-300">
                                <div class="flex items-center gap-3">
                                    {{-- Radio Bullet --}}
                                    <div class="radio-bullet flex-shrink-0">
                                        <div class="radio-outer"></div>
                                        <div class="radio-inner"></div>
                                    </div>
                                    
                                    <svg class="w-6 h-6 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-sm">Cash on Delivery (COD)</h3>
                                        <p class="text-xs text-gray-600 mt-0.5">Bayar tunai saat barang sampai</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- 📝 CATATAN PESANAN --}}
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Catatan Pesanan (Opsional)</h2>
                    <textarea wire:model="notes"
                              rows="3" 
                              placeholder="Tulis catatan untuk penjual (opsional)"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all"></textarea>
                    <p class="text-xs text-gray-500 mt-2">Contoh: "Harap kirim sebelum jam 5 sore", "Jangan dering bel, bayi tidur"</p>
                </div>

            </div>

            {{-- 💰 SUMMARY SIDEBAR --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-sm shadow-sm border border-gray-200 sticky top-24">
                    <div class="p-5 border-b border-gray-200">
                        <h2 class="text-base font-semibold text-gray-900">Ringkasan Belanja</h2>
                    </div>
                    
                    <div class="p-5 space-y-3 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ count($checkout_items) }} items)</span>
                            <span class="font-medium text-gray-900">{{ Number::currency($this->subtotal, 'IDR') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">{{ Number::currency($this->shipping_cost, 'IDR') }}</span>
                        </div>
                    </div>

                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-900">Total Pembayaran</span>
                            <span class="text-xl font-bold text-pink-600">{{ Number::currency($this->grand_total, 'IDR') }}</span>
                        </div>
                    </div>

                    <div class="p-5">
                        {{-- Loading State --}}
                        <div wire:loading wire:target="placeOrder" class="mb-3">
                            <div class="flex items-center gap-2 text-xs text-pink-600 bg-pink-50 px-3 py-2 rounded-sm">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses pesanan...</span>
                            </div>
                        </div>

                        <button wire:click="placeOrder" 
                                wire:loading.attr="disabled"
                                class="w-full px-5 py-3 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-sm transition-all disabled:bg-gray-400 disabled:cursor-not-allowed shadow-sm hover:shadow-md flex items-center justify-center gap-2 text-sm"
                                {{ !$primaryAddress ? 'disabled' : '' }}>
                            
                            <span wire:loading.remove wire:target="placeOrder">Buat Pesanan</span>
                            
                            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>

                        @if(!$primaryAddress)
                        <p class="text-xs text-center text-red-600 mt-2 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Set alamat utama terlebih dahulu
                        </p>
                        @endif

                        <a href="{{ route('cart') }}"
                           wire:navigate
                           class="block text-center text-xs text-pink-600 hover:text-pink-700 font-medium mt-3 hover:underline flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('redirect-to-payment', (event) => {
            console.log('Redirecting to payment:', event);
            window.location.href = `/user/payment/${event.orderId}`;
        });
    });
</script>
@endpush
    <style>
/* Custom Radio Button Styles */
.radio-bullet {
    position: relative;
    width: 20px;
    height: 20px;
}

.radio-outer {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    background: white;
    transition: all 0.2s ease;
}

.radio-inner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ec4899;
    transition: transform 0.2s ease;
}

/* When radio is checked */
.radio-input:checked + .radio-option {
    border-color: #ec4899 !important;
    background-color: #fdf2f8;
}

.radio-input:checked + .radio-option .radio-outer {
    border-color: #ec4899;
}

.radio-input:checked + .radio-option .radio-inner {
    transform: translate(-50%, -50%) scale(1);
}

/* Hover effects */
.radio-option:hover .radio-outer {
    border-color: #9ca3af;
}

/* Line clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions */
.radio-option {
    transition: all 0.2s ease;
}
</style>
</div>

