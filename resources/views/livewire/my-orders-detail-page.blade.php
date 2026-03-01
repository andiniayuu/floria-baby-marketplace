<div class="min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">

    <!-- Flash Messages -->
    @if(session('info'))
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-start gap-3">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('info') }}</span>
    </div>
    @endif
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start gap-3">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-3">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-4">
      <a href="{{ route('user.my-orders') }}" wire:navigate class="inline-flex items-center text-slate-600 hover:text-slate-800 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pesanan
      </a>
    </div>

    <div class="mb-6">
      <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800 mb-2">Detail Pesanan</h1>
      <p class="text-slate-600">Order #{{ $order->id }} • {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
    </div>

    @php
      // Cek status pembayaran: prioritaskan DB, fallback ke session untuk kasus race-condition webhook
      $isMidtransPaid = $order->isPaid()
        || ($order->payment_method == 'midtrans' && session('payment_success') == $order->id)
        || ($order->payment_method == 'midtrans' && $order->payment_status == 'paid');

      // Polling aktif hanya jika masih menunggu pembayaran dan belum cancelled
      $needsPolling = !$isMidtransPaid
        && $order->payment_method == 'midtrans'
        && $order->payment_status == 'pending'
        && $order->status !== 'cancelled';
    @endphp

    {{-- Wrapper polling: aktif 10 detik selama pending, otomatis berhenti saat sudah terbayar --}}
    <div @if($needsPolling) wire:poll.10s @endif>

      <!-- Status Cards Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">

        <!-- Pelanggan -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
          <div class="p-4 md:p-5 flex gap-x-4">
            <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg">
              <svg class="size-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <div class="grow">
              <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Pelanggan</p>
              <h3 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">{{ $order->user->name ?? 'N/A' }}</h3>
            </div>
          </div>
        </div>

        <!-- Tanggal -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
          <div class="p-4 md:p-5 flex gap-x-4">
            <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg">
              <svg class="size-5 text-purple-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <div class="grow">
              <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Tanggal Pesanan</p>
              <h3 class="text-lg sm:text-xl font-semibold text-gray-800">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</h3>
            </div>
          </div>
        </div>

        <!-- Status Pesanan -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
          <div class="p-4 md:p-5 flex gap-x-4">
            <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-green-100 to-green-200 rounded-lg">
              <svg class="size-5 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6"/><path d="m12 12 4 10 1.7-4.3L22 16Z"/>
              </svg>
            </div>
            <div class="grow">
              <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Status Pesanan</p>
              @php
                $statusBadge = match($order->status) {
                  'new'        => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white">🆕 Baru</span>',
                  'processing' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white">⚙️ Diproses</span>',
                  'shipped'    => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">🚚 Dikirim</span>',
                  'delivered'  => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-700 text-white">✅ Terkirim</span>',
                  'cancelled'  => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-700 text-white">❌ Dibatalkan</span>',
                  default      => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-300 text-gray-800">'.ucfirst($order->status).'</span>',
                };
              @endphp
              {!! $statusBadge !!}
            </div>
          </div>
        </div>

        <!-- Status Pembayaran -->
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
          <div class="p-4 md:p-5 flex gap-x-4">
            <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg">
              <svg class="size-5 text-orange-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
              </svg>
            </div>
            <div class="grow">
              <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Status Pembayaran</p>
              @php
                if ($isMidtransPaid) {
                  $paymentBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">✅ Lunas</span>';
                } else {
                  $paymentBadge = match($order->payment_status) {
                    'pending' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-400 text-white">⏳ Menunggu Pembayaran</span>',
                    'paid'    => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">✅ Lunas</span>',
                    'failed'  => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">❌ Gagal</span>',
                    default   => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-300 text-gray-800">'.ucfirst($order->payment_status).'</span>',
                  };
                }
              @endphp
              {!! $paymentBadge !!}

              {{-- Indikator live polling --}}
              @if($needsPolling)
              <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                <span class="inline-block w-2 h-2 rounded-full bg-orange-400 animate-ping"></span>
                Memperbarui otomatis...
              </p>
              @endif
            </div>
          </div>
        </div>

      </div>

      <div class="flex flex-col lg:flex-row gap-6">

        <!-- Main Content -->
        <div class="lg:w-2/3">

          <!-- Order Items Table -->
          <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Barang Pesanan ({{ $order->items->count() }})
              </h2>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="text-left font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3">Produk</th>
                    <th class="hidden sm:table-cell text-right font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[140px]">Harga Satuan</th>
                    <th class="hidden sm:table-cell text-center font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[100px]">Jumlah</th>
                    <th class="text-right font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[140px]">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  @foreach ($order->items as $item)
                  <tr wire:key="item-{{ $item->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                      <div class="flex items-center gap-3">
                        <img class="h-14 w-14 sm:h-16 sm:w-16 object-cover rounded-lg border border-gray-200 flex-shrink-0"
                             src="{{ url('storage', $item->product->images[0] ?? 'default.jpg') }}"
                             alt="{{ $item->product->name }}">
                        <div class="flex-1 min-w-0">
                          <p class="font-semibold text-sm sm:text-base text-gray-800 line-clamp-2">{{ $item->product->name }}</p>
                          <div class="sm:hidden text-xs text-gray-500 mt-1">
                            <p>{{ Number::currency($item->unit_amount, 'IDR') }} × {{ $item->quantity }}</p>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="hidden sm:table-cell px-6 py-4 text-right text-sm text-gray-700">
                      {{ Number::currency($item->unit_amount, 'IDR') }}
                    </td>
                    <td class="hidden sm:table-cell px-6 py-4 text-center">
                      <span class="inline-flex items-center justify-center px-3 py-1 bg-gray-100 rounded-full text-sm font-medium text-gray-800">
                        {{ $item->quantity }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                      {{ Number::currency($item->total_amount, 'IDR') }}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Shipping Address -->
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Alamat Pengiriman
              </h2>
            </div>
            <div class="p-6 space-y-4">
              @php $address = $order->address; @endphp
              @if($address)
              <div class="pb-4 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                  <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900">{{ $address->recipient_name }}</h3>
                    @if($address->label)
                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded mt-1">{{ $address->label }}</span>
                    @endif
                  </div>
                </div>
                <p class="text-sm text-gray-600 font-medium">{{ $address->phone }}</p>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</p>
                  <p class="text-sm text-gray-700 leading-relaxed">
                    {{ $address->street_address }}
                    @if($address->city), {{ $address->city }}@endif
                    @if($address->state), {{ $address->state }}@endif
                    @if($address->zip_code) {{ $address->zip_code }}@endif
                    @if($address->country), {{ $address->country }}@endif
                  </p>
                </div>
              </div>
              @if($address->notes)
              <div class="flex items-start gap-3 pt-3 border-t border-gray-100">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-amber-200 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500 mb-1">Catatan Alamat</p>
                  <p class="text-sm text-gray-700 italic">{{ $address->notes }}</p>
                </div>
              </div>
              @endif
              @else
              <p class="text-sm text-gray-500 italic">Alamat tidak tersedia.</p>
              @endif

              <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">{{ ucfirst($order->shipping_method ?? 'Standard') }} Shipping</p>
                  <p class="text-sm text-gray-600">
                    {{ Number::currency($order->shipping_cost ?? 0, 'IDR') }}
                    @if($order->shipping_method == 'regular') • Estimasi 3-5 hari kerja
                    @elseif($order->shipping_method == 'express') • Estimasi 1-2 hari kerja
                    @endif
                  </p>
                </div>
              </div>

              @if($order->notes)
              <div class="pt-4 border-t border-gray-200">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                  <p class="text-xs text-amber-600 font-semibold mb-1">Catatan Pesanan:</p>
                  <p class="text-sm text-amber-800">{{ $order->notes }}</p>
                </div>
              </div>
              @endif
            </div>
          </div>

        </div>

        <!-- Summary Sidebar -->
        <div class="lg:w-1/3">
          <div class="bg-white rounded-xl shadow-md overflow-hidden lg:sticky lg:top-6">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Ringkasan Pesanan
              </h2>
            </div>

            <div class="p-6 space-y-4">

              <!-- Price Breakdown -->
              <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Subtotal ({{ $order->items->count() }} produk)</span>
                <span class="font-medium text-gray-900">{{ Number::currency($order->items->sum('total_amount'), 'IDR') }}</span>
              </div>
              <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Ongkos Kirim ({{ ucfirst($order->shipping_method ?? '-') }})</span>
                <span class="font-medium text-gray-900">{{ Number::currency($order->shipping_cost ?? 0, 'IDR') }}</span>
              </div>
              @if(isset($order->discount_amount) && $order->discount_amount > 0)
              <div class="flex justify-between items-center text-sm text-green-600">
                <span>Diskon</span>
                <span class="font-semibold">-{{ Number::currency($order->discount_amount, 'IDR') }}</span>
              </div>
              @endif
              <div class="border-t-2 border-gray-200 pt-4">
                <div class="flex justify-between items-center">
                  <span class="text-base font-semibold text-gray-800">Grand Total</span>
                  <span class="text-xl font-bold text-blue-600">{{ Number::currency($order->grand_total, 'IDR') }}</span>
                </div>
              </div>

              <!-- Payment Method Info -->
              <div class="pt-4 border-t border-gray-200">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Metode Pembayaran</p>
                    <p class="text-sm font-semibold text-gray-800">
                      @if($order->payment_method == 'midtrans') 💳 Midtrans (Bank / E-Wallet)
                      @elseif($order->payment_method == 'cod') 💵 Bayar di Tempat (COD)
                      @else {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                      @endif
                    </p>
                    @if($order->midtrans_payment_type)
                    <p class="text-xs text-gray-500 mt-0.5">via {{ ucwords(str_replace('_', ' ', $order->midtrans_payment_type)) }}</p>
                    @endif
                  </div>
                </div>
              </div>

              {{-- ============================================================
                  BLOK PEMBAYARAN — 4 state: Lunas | Pending | COD | Cancelled
              =============================================================== --}}

              @if($isMidtransPaid)
                {{-- ✅ LUNAS --}}
                <div class="pt-4 border-t border-gray-200">
                  <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-3">
                      <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                      </div>
                      <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full uppercase">✅ Pembayaran Lunas</span>
                    </div>
                    <div class="space-y-2">
                      @if($order->midtrans_payment_type)
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Metode</span>
                        <span class="font-semibold text-gray-900">
                          @php
                            $pt = $order->midtrans_payment_type ?? '';
                            echo match(true) {
                              str_contains($pt, 'credit_card') => '💳 Kartu Kredit/Debit',
                              str_contains($pt, 'bca')        => '🏦 BCA Virtual Account',
                              str_contains($pt, 'bni')        => '🏦 BNI Virtual Account',
                              str_contains($pt, 'bri')        => '🏦 BRI Virtual Account',
                              str_contains($pt, 'mandiri')    => '🏦 Mandiri Bill',
                              str_contains($pt, 'permata')    => '🏦 Permata VA',
                              str_contains($pt, 'gopay')      => '💚 GoPay',
                              str_contains($pt, 'shopeepay')  => '🧡 ShopeePay',
                              str_contains($pt, 'qris')       => '📱 QRIS',
                              str_contains($pt, 'other_va')   => '🏦 Virtual Account',
                              $pt !== ''                      => '💳 ' . ucwords(str_replace('_', ' ', $pt)),
                              default                         => '💳 Transfer / E-Wallet',
                            };
                          @endphp
                        </span>
                      </div>
                      @endif
                      @if($order->paid_at)
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Waktu Bayar</span>
                        <span class="font-semibold text-gray-900">{{ $order->paid_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                      </div>
                      @endif
                      @if($order->midtrans_transaction_id)
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-600">ID Transaksi</span>
                        <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $order->midtrans_transaction_id }}</span>
                      </div>
                      @endif
                      <div class="flex justify-between items-center text-sm pt-2 border-t border-green-200">
                        <span class="font-bold text-gray-900">Total Dibayar</span>
                        <span class="font-bold text-green-700">{{ Number::currency($order->grand_total, 'IDR') }}</span>
                      </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-green-200">
                      <p class="text-xs text-green-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Pembayaran diverifikasi & aman melalui Midtrans
                      </p>
                    </div>
                  </div>
                </div>

              @elseif($order->payment_method == 'midtrans' && !$isMidtransPaid && $order->status !== 'cancelled')
                {{-- ⏳ MIDTRANS - MENUNGGU PEMBAYARAN --}}
                <div class="pt-4 border-t border-gray-200">
                  <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-3">
                    <div class="flex items-start gap-2">
                      <svg class="w-4 h-4 text-orange-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                      </svg>
                      <div>
                        <p class="text-xs text-orange-800 font-medium">⏳ Menunggu pembayaran. Selesaikan untuk memproses pesanan Anda.</p>
                        @if($needsPolling)
                        <p class="text-xs text-orange-600 mt-1 flex items-center gap-1">
                          <span class="inline-block w-1.5 h-1.5 rounded-full bg-orange-400 animate-ping"></span>
                          Status diperbarui otomatis setiap 10 detik
                        </p>
                        @endif
                      </div>
                    </div>
                  </div>

                  @if($order->snap_token)
                  <button id="pay-button"
                          onclick="openSnapPayment()"
                          class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 active:bg-blue-800 transition-all shadow-sm gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    💳 Bayar Sekarang
                  </button>
                  @else
                  <a href="{{ route('user.payment.show', $order->id) }}"
                     class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-all shadow-sm gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    💳 Bayar Sekarang
                  </a>
                  @endif
                </div>

              @elseif($order->payment_method == 'cod')
                {{-- 💵 COD --}}
                <div class="pt-4 border-t border-gray-200">
                  <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <span class="px-3 py-1 bg-amber-200 text-amber-900 text-xs font-bold rounded-full uppercase">💵 Bayar di Tempat (COD)</span>
                    <p class="text-sm text-amber-800 mt-3">Pembayaran akan ditagih saat pesanan tiba. Siapkan uang pas.</p>
                  </div>
                </div>

              @elseif($order->status === 'cancelled')
                {{-- ❌ DIBATALKAN --}}
                <div class="pt-4 border-t border-gray-200">
                  <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <span class="px-3 py-1 bg-red-200 text-red-900 text-xs font-bold rounded-full uppercase">❌ Pesanan Dibatalkan</span>
                    <p class="text-sm text-red-800 mt-3">Pesanan ini telah dibatalkan atau pembayaran gagal/kedaluwarsa.</p>
                  </div>
                </div>
              @endif

              <!-- Action Buttons -->
              @if($order->status != 'cancelled')
              <div class="pt-4 border-t border-gray-200 space-y-3">
              
                {{-- ✅ TAMBAHKAN INI: Tombol konfirmasi terima saat status shipped --}}
                @if($order->status === 'shipped')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-1">
                  <p class="text-xs text-blue-800 font-medium mb-1">📦 Pesanan sedang dalam perjalanan</p>
                  <p class="text-xs text-blue-600">
                    Sudah menerima paket? Klik tombol di bawah untuk konfirmasi.
                  </p>
                  @if($order->tracking_number)
                  <p class="text-xs text-blue-500 mt-1 font-mono">
                    No. Resi: {{ $order->tracking_number }}
                  </p>
                  @endif
                </div>
              
                <button wire:click="confirmReceived"
                        wire:confirm="Konfirmasi bahwa pesanan sudah Anda terima?"
                        wire:loading.attr="disabled"
                        wire:target="confirmReceived"
                        class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 active:bg-green-800 transition-all shadow-sm gap-2 text-sm disabled:opacity-60">

                  {{-- Normal --}}
                  <span wire:loading.remove wire:target="confirmReceived"
                        class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Pesanan Sudah Diterima
                  </span>
                
                  {{-- Loading --}}
                  <span wire:loading wire:target="confirmReceived"
                        class="inline-flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Mengkonfirmasi...
                  </span>
                </button>
                @endif
              
                {{-- Tombol Belanja Lagi — sudah ada, pindah ke delivered --}}
                @if($order->status == 'delivered')
                <a href="{{ route('products') }}" wire:navigate
                   class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-all shadow-sm text-sm">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                  Belanja Lagi
                </a>
                @endif
              
                <button class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-white border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all text-sm">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Hubungi Support
                </button>
              </div>
              @endif

            </div>
          </div>
        </div>

      </div>
    </div>{{-- end wire:poll wrapper --}}

  </div>
</div>

{{-- Midtrans Snap — hanya load jika ada snap_token dan belum bayar --}}
@if($order->snap_token && !$isMidtransPaid && $order->payment_method == 'midtrans')
<script src="{{ config('services.midtrans.is_production')
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
<script>
    function openSnapPayment() {
        const btn = document.getElementById('pay-button');
        if (!btn) return;

        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg> Memuat...`;

        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function (result) {
                window.location.href = '{{ route('user.order.success', $order->id) }}';
            },
            onPending: function (result) {
                window.location.reload();
            },
            onError: function (result) {
                window.location.reload();
            },
            onClose: function () {
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg> 💳 Bayar Sekarang`;
            }
        });
    }
</script>
@endif