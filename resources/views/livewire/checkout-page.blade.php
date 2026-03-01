<div class="min-h-screen bg-gray-50 pt-18 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('cart') }}" wire:navigate
               class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-sm hover:bg-pink-50 hover:border-pink-300 transition-all">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Checkout</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ count($checkout_items) }} produk dalam pesanan</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @error('checkout')
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ========== KOLOM KIRI ========== --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- ORDER ITEMS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-pink-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            Produk Dipesan
                        </h2>
                        <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">{{ count($checkout_items) }} item</span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($checkout_items as $item)
                        <div class="flex items-center gap-4 px-5 py-4">
                            <div class="relative flex-shrink-0">
                                <img class="w-14 h-14 rounded-xl object-cover border border-gray-100"
                                     src="{{ url('storage', $item['image']) }}"
                                     alt="{{ $item['name'] }}">
                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-pink-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm line-clamp-1 mb-1">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ Number::currency($item['unit_amount'], 'IDR') }} × {{ $item['quantity'] }}
                                    @if(isset($item['weight'])) · <span class="text-gray-400">{{ number_format($item['weight']) }}gr/pcs</span> @endif
                                </p>
                            </div>
                            <p class="font-bold text-gray-900 text-sm flex-shrink-0">{{ Number::currency($item['total_amount'], 'IDR') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                            Total berat paket
                        </span>
                        <span class="text-xs font-bold text-gray-700 bg-white border border-gray-200 px-3 py-1 rounded-full">
                            {{ number_format($this->totalWeight) }}gr · {{ $this->totalWeightKg }}kg
                        </span>
                    </div>
                </div>

                {{-- ALAMAT PENGIRIMAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            Alamat Pengiriman
                        </h2>
                        <a href="{{ route('user.addresses.create') }}" target="_blank"
                           class="text-xs text-pink-600 hover:text-pink-700 font-semibold flex items-center gap-1 bg-pink-50 px-3 py-1.5 rounded-full hover:bg-pink-100 transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Alamat
                        </a>
                    </div>
                    <div class="p-5">
                        @php $primaryAddress = $addresses->firstWhere('is_primary', true); @endphp
                        @if($primaryAddress)
                            <div class="border-2 border-pink-400 bg-pink-50 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-full bg-pink-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 bg-pink-500 text-white text-xs font-bold rounded-full">Alamat Utama</span>
                                            @if($primaryAddress->label)
                                            <span class="px-2 py-0.5 bg-white border border-gray-200 text-gray-600 text-xs font-medium rounded-full">{{ $primaryAddress->label }}</span>
                                            @endif
                                        </div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $primaryAddress->recipient_name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $primaryAddress->phone }}</p>
                                        <p class="text-sm text-gray-700 mt-1.5 leading-relaxed">{{ $primaryAddress->formatted_address }}</p>
                                        @if($primaryAddress->notes)
                                        <p class="text-xs text-gray-500 italic mt-2 pt-2 border-t border-pink-200">📝 {{ $primaryAddress->notes }}</p>
                                        @endif
                                        <a href="{{ route('user.addresses.index') }}" target="_blank"
                                           class="inline-block text-xs text-pink-600 hover:text-pink-700 font-semibold mt-3">
                                            Pilih alamat lain →
                                        </a>
                                    </div>
                                    <div class="w-5 h-5 rounded-full bg-pink-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-semibold text-sm mb-1">Belum Ada Alamat Utama</p>
                                <p class="text-xs text-gray-500 mb-4">Atur alamat utama untuk melanjutkan checkout</p>
                                <a href="{{ route('user.addresses.index') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-full transition-all text-xs shadow-sm">
                                    Kelola Alamat
                                </a>
                            </div>
                            @error('selected_address_id')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                {{-- METODE PENGIRIMAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                </svg>
                            </div>
                            Metode Pengiriman
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="mb-4 flex items-center gap-2 text-xs text-blue-700 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5">
                            <svg class="w-4 h-4 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Ongkir dihitung dari berat total <strong>{{ number_format($this->totalWeight) }}gr ({{ $this->totalWeightKg }}kg)</strong>. Min. charge berlaku.</span>
                        </div>

                        @php
                            $regularCost = max($this->totalWeightKg * 8000, 15000);
                            $expressCost = max($this->totalWeightKg * 15000, 25000);
                        @endphp

                        <div class="space-y-3">
                            <label class="block cursor-pointer">
                                <input type="radio" wire:model.live="shipping_method" value="regular" name="shipping_selection" class="hidden radio-input">
                                <div class="radio-option flex items-center justify-between border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="radio-bullet flex-shrink-0">
                                            <div class="radio-outer"></div>
                                            <div class="radio-inner"></div>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">Reguler</p>
                                            <p class="text-xs text-gray-500 mt-0.5">3–5 hari kerja · Rp8.000/kg</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">{{ Number::currency($regularCost, 'IDR') }}</span>
                                </div>
                            </label>

                            <label class="block cursor-pointer">
                                <input type="radio" wire:model.live="shipping_method" value="express" name="shipping_selection" class="hidden radio-input">
                                <div class="radio-option flex items-center justify-between border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="radio-bullet flex-shrink-0">
                                            <div class="radio-outer"></div>
                                            <div class="radio-inner"></div>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900 text-sm">Express</p>
                                                <span class="text-[10px] font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded-full">CEPAT</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-0.5">1–2 hari kerja · Rp15.000/kg</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">{{ Number::currency($expressCost, 'IDR') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            Metode Pembayaran
                        </h2>
                    </div>
                    <div class="p-5 space-y-3">
                        <label class="block cursor-pointer">
                            <input type="radio" wire:model.live="payment_method" value="midtrans" name="payment_selection" class="hidden radio-input">
                            <div class="radio-option border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="radio-bullet flex-shrink-0"><div class="radio-outer"></div><div class="radio-inner"></div></div>
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 text-sm">Transfer Bank / E-Wallet</p>
                                        <p class="text-xs text-gray-500 mt-0.5">BCA · BNI · Mandiri · GoPay · OVO · QRIS</p>
                                        <span class="inline-block mt-1.5 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">via Midtrans</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="block cursor-pointer">
                            <input type="radio" wire:model.live="payment_method" value="cod" name="payment_selection" class="hidden radio-input">
                            <div class="radio-option border-2 border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="radio-bullet flex-shrink-0"><div class="radio-outer"></div><div class="radio-inner"></div></div>
                                    <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Cash on Delivery (COD)</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Bayar tunai saat barang sampai</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            Catatan Pesanan
                            <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                        </h2>
                    </div>
                    <div class="p-5">
                        <textarea wire:model="notes" rows="3"
                                  placeholder="Contoh: Harap kirim sebelum jam 5 sore, jangan dering bel..."
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-300 focus:border-pink-400 transition-all resize-none placeholder-gray-400"></textarea>
                    </div>
                </div>

            </div>{{-- /col-span-2 --}}

            {{-- ========== KOLOM KANAN (STICKY) ========== --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24 overflow-hidden">

                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800">Ringkasan Belanja</h2>
                    </div>

                    {{-- Rincian --}}
                    <div class="px-5 py-4 space-y-3 border-b border-gray-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Subtotal <span class="text-gray-400">({{ count($checkout_items) }} produk)</span></span>
                            <span class="font-semibold text-gray-900">{{ Number::currency($this->subtotal, 'IDR') }}</span>
                        </div>

                        <div class="flex justify-between items-start text-sm">
                            <div>
                                <p class="text-gray-500">Ongkos Kirim</p>
                                @if($shipping_method)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $shippingInfo['label'] }} · {{ $shippingInfo['eta'] }}</p>
                                @else
                                    <p class="text-xs text-orange-500 mt-0.5">Pilih metode pengiriman</p>
                                @endif
                            </div>
                            @if($shipping_method)
                                <span class="font-semibold text-gray-900">{{ Number::currency($this->shippingCost, 'IDR') }}</span>
                            @else
                                <span class="text-gray-300 font-medium">—</span>
                            @endif
                        </div>

                        @if($payment_method)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Pembayaran</span>
                            <span class="text-xs font-bold bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">
                                @if($payment_method == 'midtrans') 💳 Bank / E-Wallet
                                @elseif($payment_method == 'cod') 💵 COD
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Grand Total --}}
                    <div class="px-5 py-4 bg-gradient-to-r from-pink-50 to-rose-50 border-b border-pink-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Total Pembayaran</p>
                                <p class="text-xs text-gray-400 mt-0.5">Termasuk ongkos kirim</p>
                            </div>
                            <p class="text-2xl font-bold text-pink-600">{{ Number::currency($this->grandTotal, 'IDR') }}</p>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="px-5 py-4">
                        <div wire:loading wire:target="placeOrder" class="mb-3 flex items-center gap-2 text-xs text-pink-600 bg-pink-50 border border-pink-100 px-3 py-2 rounded-xl">
                            <svg class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Memproses pesanan...
                        </div>

                        <button wire:click="placeOrder"
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 disabled:bg-gray-200 disabled:cursor-not-allowed text-white font-bold rounded-xl transition-all hover:shadow-lg hover:shadow-pink-200 flex items-center justify-center gap-2 text-sm"
                            @if(!$primaryAddress) disabled @endif>
                            <span wire:loading.remove wire:target="placeOrder" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Buat Pesanan
                            </span>
                            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        @if(!$primaryAddress)
                        <p class="text-xs text-center text-red-500 mt-2">⚠️ Atur alamat utama terlebih dahulu</p>
                        @endif

                        <p class="text-xs text-center text-gray-400 mt-3 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Transaksi aman & terenkripsi
                        </p>

                        <a href="{{ route('cart') }}" wire:navigate
                           class="flex items-center justify-center gap-1 text-xs text-pink-600 hover:text-pink-700 font-medium mt-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Cart
                        </a>

                        {{-- ===== KALKULASI ONGKIR (di bawah tombol, muncul setelah pilih pengiriman) ===== --}}
                        @if($shipping_method)
                        <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                            <p class="text-xs font-bold text-gray-600 mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 17h.01M15 17h.01M4 7h16a1 1 0 011 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8a1 1 0 011-1z"/>
                                </svg>
                                Rincian Kalkulasi Ongkir
                            </p>
                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                        Berat total
                                    </span>
                                    <span class="font-semibold text-gray-700">{{ number_format($shippingInfo['weight_gram']) }}gr = {{ $shippingInfo['weight_kg'] }}kg</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                        Tarif {{ $shippingInfo['label'] }}
                                    </span>
                                    <span class="font-semibold text-gray-700">{{ Number::currency($shippingInfo['rate_per_kg'], 'IDR') }}/kg</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                        {{ $shippingInfo['weight_kg'] }}kg × {{ Number::currency($shippingInfo['rate_per_kg'], 'IDR') }}
                                    </span>
                                    <span class="font-semibold text-gray-700">{{ Number::currency($shippingInfo['calculated'], 'IDR') }}</span>
                                </div>
                                @if($shippingInfo['final'] > $shippingInfo['calculated'])
                                <div class="flex justify-between items-center text-xs text-blue-600 bg-blue-50 rounded-lg px-2 py-1.5">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                        Min. charge berlaku
                                    </span>
                                    <span class="font-bold">{{ Number::currency($shippingInfo['minimum'], 'IDR') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center text-xs pt-2 border-t border-gray-200">
                                    <span class="font-bold text-gray-700">Ongkir dikenakan</span>
                                    <span class="font-bold text-pink-600 text-sm">{{ Number::currency($this->shippingCost, 'IDR') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Midtrans --}}
    <script src="{{ config('services.midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('open-midtrans-popup', function (event) {
            const data = event.detail[0];
            if (!data.token) { console.error('Snap token tidak ditemukan'); return; }
            window.snap.pay(data.token, {
                onSuccess: () => window.location.href = data.successUrl,
                onPending: () => window.location.href = data.pendingUrl,
                onError:   () => window.location.href = data.pendingUrl,
                onClose:   () => window.location.href = data.myOrderUrl,
            });
        });
    </script>

    <style>
        .radio-bullet { position: relative; width: 20px; height: 20px; flex-shrink: 0; }
        .radio-outer { width: 20px; height: 20px; border: 2px solid #d1d5db; border-radius: 50%; background: white; transition: all 0.2s ease; }
        .radio-inner { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0); width: 10px; height: 10px; border-radius: 50%; background: #ec4899; transition: transform 0.2s ease; }
        .radio-input:checked + .radio-option { border-color: #ec4899 !important; background-color: #fdf2f8; }
        .radio-input:checked + .radio-option .radio-outer { border-color: #ec4899; }
        .radio-input:checked + .radio-option .radio-inner { transform: translate(-50%, -50%) scale(1); }
        .radio-option { transition: all 0.2s ease; }
    </style>
</div>