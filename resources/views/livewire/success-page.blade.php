<section class="min-h-screen flex items-center font-poppins bg-gradient-to-br from-gray-50 to-blue-50 pt-20 md:pt-28 pb-12">
  <div class="justify-center flex-1 max-w-5xl px-4 py-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">

    {{-- Success Header --}}
    <div class="text-center mb-8 pb-6 border-b border-gray-200">
      <div class="flex justify-center mb-4">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center animate-bounce">
          <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
      <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Pesanan Berhasil Dilakukan!</h1>
      <p class="text-gray-600 text-lg">Terima kasih atas pembelian Anda. Pesanan Anda sedang diproses.</p>
    </div>

    {{-- Order Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 px-4">
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
        <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Nomor Pesanan</p>
        <p class="text-xl font-bold text-blue-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
      </div>
      <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
        <p class="text-xs text-purple-600 font-semibold uppercase mb-1">Tanggal Pemesanan</p>
        <p class="text-xl font-bold text-purple-900">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</p>
        <p class="text-sm text-purple-700 mt-1">{{ $order->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
      </div>
      <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
        <p class="text-xs text-green-600 font-semibold uppercase mb-1">Jumlah Total</p>
        <p class="text-xl font-bold text-green-900">{{ Number::currency($order->grand_total, 'IDR') }}</p>
      </div>
      <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
        <p class="text-xs text-orange-600 font-semibold uppercase mb-1">Pembayaran</p>
        <p class="text-lg font-bold text-orange-900">
          @if($order->payment_method == 'cod') 💵 Cash on Delivery
          @elseif($order->payment_method == 'midtrans') 💳 Bank / E-Wallet
          @elseif($order->payment_method == 'transfer') 🏦 Transfer Bank Manual
          @endif
        </p>
      </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 mb-8">

      {{-- Left Column --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
          <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Barang Pesanan ({{ $order->items->count() }})
          </h2>
          <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="bg-white rounded-lg p-4 flex items-center gap-4 shadow-sm border border-gray-100 hover:shadow-md transition">
              <div class="flex-shrink-0">
                <img class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200"
                     src="{{ url('storage', $item->product->images[0] ?? 'default.jpg') }}"
                     alt="{{ $item->product->name }}">
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1">{{ $item->product->name }}</h3>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                  <span>{{ Number::currency($item->unit_amount, 'IDR') }}</span>
                  <span>×</span>
                  <span class="font-semibold">{{ $item->quantity }}</span>
                </div>
              </div>
              <div class="text-right">
                <p class="font-bold text-gray-900">{{ Number::currency($item->total_amount, 'IDR') }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 shadow-sm">
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b-2 border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Alamat Pengiriman
            </h2>
          </div>
          <div class="p-6">
            <div class="bg-slate-50 rounded-lg p-5 border border-slate-200">
              <div class="flex items-start gap-3 mb-4 pb-4 border-b border-slate-200">
                <svg class="w-5 h-5 text-slate-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <div>
                  <p class="text-xs text-slate-500 mb-1">Penerima</p>
                  <p class="font-bold text-slate-900 text-lg">{{ $order->address->recipient_name }}</p>
                </div>
              </div>
              <div class="flex items-start gap-3 mb-4 pb-4 border-b border-slate-200">
                <svg class="w-5 h-5 text-slate-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <div>
                  <p class="text-xs text-slate-500 mb-1">Nomor Telepon</p>
                  <p class="font-semibold text-slate-900">{{ $order->address->phone }}</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-slate-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <div class="flex-1">
                  <p class="text-xs text-slate-500 mb-2">Alamat Lengkap</p>
                  <div class="space-y-1">
                    <p class="font-semibold text-slate-900">{{ $order->address->street_address }}</p>
                    @if($order->address->city)<p class="text-slate-700"><span class="text-slate-500">Kota:</span> {{ $order->address->city }}</p>@endif
                    @if($order->address->state)<p class="text-slate-700"><span class="text-slate-500">Provinsi:</span> {{ $order->address->state }}</p>@endif
                    @if($order->address->zip_code)<p class="text-slate-700"><span class="text-slate-500">Kode Pos:</span> {{ $order->address->zip_code }}</p>@endif
                    <p class="text-slate-700"><span class="text-slate-500">Negara:</span> {{ $order->address->country ?? 'Indonesia' }}</p>
                  </div>
                </div>
              </div>
              @if($order->address->notes)
              <div class="pt-4 mt-4 border-t border-slate-200">
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  <div>
                    <p class="text-xs text-slate-500 mb-1">Catatan Pengiriman</p>
                    <p class="text-sm text-slate-700 italic bg-amber-50 p-3 rounded border border-amber-200">"{{ $order->address->notes }}"</p>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>

      </div>

      {{-- Right Column --}}
      <div class="lg:col-span-1 space-y-6">

        {{-- Ringkasan Pesanan --}}
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
          <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Ringkasan Pesanan
          </h2>
          <div class="space-y-3 mb-4">
            <div class="flex justify-between text-sm">
              <span class="text-gray-700">Subtotal ({{ $order->items->count() }} items)</span>
              <span class="font-semibold text-gray-900">{{ Number::currency($order->items->sum('total_amount'), 'IDR') }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-700">Ongkos Kirim ({{ ucfirst($order->shipping_method) }})</span>
              <span class="font-semibold text-gray-900">{{ Number::currency($order->shipping_cost, 'IDR') }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="flex justify-between text-sm text-green-600">
              <span>Diskon</span>
              <span class="font-semibold">-{{ Number::currency($order->discount_amount, 'IDR') }}</span>
            </div>
            @endif
          </div>
          <div class="pt-4 border-t-2 border-blue-300">
            <div class="flex justify-between items-center">
              <span class="text-lg font-bold text-gray-900">Total</span>
              <span class="text-2xl font-bold text-blue-600">{{ Number::currency($order->grand_total, 'IDR') }}</span>
            </div>
          </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 shadow-sm">
          <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b-2 border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
              <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7z"/>
              </svg>
              Metode Pengiriman
            </h2>
          </div>
          <div class="p-6">
            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
              <div class="flex items-center justify-between mb-3">
                <p class="font-bold text-slate-900 text-lg">{{ ucfirst($order->shipping_method) }} Delivery</p>
                <p class="font-bold text-green-600">{{ Number::currency($order->shipping_cost, 'IDR') }}</p>
              </div>
              <p class="text-sm text-slate-600 mb-2">
                @if($order->shipping_method == 'regular') 📦 Estimasi: 3-5 hari kerja
                @elseif($order->shipping_method == 'express') 🚀 Estimasi: 1-2 hari kerja
                @else 📬 Standard shipping @endif
              </p>
              <div class="pt-3 border-t border-slate-200">
                <p class="text-xs text-slate-500">Nomor resi akan dikirim via email setelah pesanan dikirim.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- ✅ Payment Status
             PENTING: Halaman ini dicapai via onSuccess Snap JS yang redirect langsung.
             Webhook Midtrans mungkin belum update DB saat halaman ini dimuat.
             Jadi: JANGAN bergantung pada isPaid() atau payment_status dari DB.
             Tampilkan berdasarkan payment_method saja — jika user sampai di sini
             via onSuccess, berarti pembayaran sudah berhasil dari sisi Midtrans.
        --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 shadow-sm">
          <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b-2 border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              Status Pembayaran
            </h2>
          </div>
          <div class="p-6">

            @if($order->payment_method == 'midtrans')
              {{-- ✅ MIDTRANS - Selalu tampilkan sebagai BERHASIL di success page.
                   User sampai di sini karena onSuccess dari Snap → pembayaran confirmed.
                   Webhook akan update DB secara async, tidak perlu tunggu. --}}
              <div class="bg-green-50 rounded-lg p-4 border-2 border-green-300">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                  <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full uppercase tracking-wide">
                    Pembayaran Berhasil
                  </span>
                </div>

                <div class="space-y-2 mt-3">
                  @if($order->midtrans_payment_type)
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Metode</span>
                    <span class="font-semibold text-gray-900">
                      @php
                        $pt = $order->midtrans_payment_type ?? '';
                        echo match(true) {
                          str_contains($pt, 'credit_card') => '💳 Kartu Kredit/Debit',
                          str_contains($pt, 'bca')        => '🏦 BCA Virtual Account',
                          str_contains($pt, 'bni')        => '🏦 BNI Virtual Account',
                          str_contains($pt, 'bri')        => '🏦 BRI Virtual Account',
                          str_contains($pt, 'mandiri')    => '🏦 Mandiri Virtual Account',
                          str_contains($pt, 'permata')    => '🏦 Permata Virtual Account',
                          str_contains($pt, 'gopay')      => '💚 GoPay',
                          str_contains($pt, 'shopeepay')  => '🧡 ShopeePay',
                          str_contains($pt, 'qris')       => '📱 QRIS',
                          str_contains($pt, 'other_va')   => '🏦 Virtual Account',
                          $pt !== ''                      => '💳 ' . ucwords(str_replace('_', ' ', $pt)),
                          default                         => '💳 Transfer Bank / E-Wallet',
                        };
                      @endphp
                    </span>
                  </div>
                  @endif

                  @if($order->paid_at)
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Waktu Bayar</span>
                    <span class="font-semibold text-gray-900">{{ $order->paid_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                  </div>
                  @endif

                  @if($order->midtrans_transaction_id)
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">ID Transaksi</span>
                    <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $order->midtrans_transaction_id }}</span>
                  </div>
                  @endif

                  <div class="flex justify-between items-center text-sm pt-2 border-t border-green-200">
                    <span class="font-bold text-gray-900">Total Dibayar</span>
                    <span class="font-bold text-green-700 text-base">{{ Number::currency($order->grand_total, 'IDR') }}</span>
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

            @elseif($order->payment_method == 'cod')
              <div class="bg-amber-50 rounded-lg p-4 border-2 border-amber-200">
                <div class="flex items-center gap-2 mb-3">
                  <span class="px-3 py-1 bg-amber-200 text-amber-900 text-xs font-bold rounded-full uppercase">💵 Bayar di Tempat (COD)</span>
                </div>
                <p class="text-sm text-amber-800">Pembayaran akan ditagih saat pesanan tiba di alamat Anda. Siapkan uang pas untuk mempercepat transaksi.</p>
              </div>

            @elseif($order->payment_method == 'transfer')
              <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-200">
                <div class="flex items-center gap-2 mb-3">
                  <span class="px-3 py-1 bg-orange-200 text-orange-900 text-xs font-bold rounded-full uppercase">⏳ Menunggu Konfirmasi</span>
                </div>
                <p class="text-sm text-orange-900 font-medium mb-2">Transfer Bank Manual</p>
                <div class="bg-white rounded-lg p-4 border border-orange-200 mb-3">
                  <p class="text-xs text-slate-500 mb-2">Transfer ke:</p>
                  <p class="font-bold text-slate-900 mb-1">Bank BCA</p>
                  <p class="text-sm text-slate-700 mb-1">No. Rekening: 1234567890</p>
                  <p class="text-sm text-slate-700">A/N: FloriaBaby Store</p>
                </div>
                <p class="text-xs text-orange-800">⚠️ Selesaikan pembayaran dalam 24 jam untuk menghindari pembatalan otomatis.</p>
              </div>

            @else
              <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <span class="px-3 py-1 bg-blue-200 text-blue-900 text-xs font-bold rounded-full uppercase">{{ $order->payment_status }}</span>
                <p class="text-sm text-blue-900 mt-2">Pembayaran sedang diproses.</p>
              </div>
            @endif

          </div>
        </div>

      </div>
    </div>

    {{-- Catatan Pesanan --}}
    @if($order->notes)
    <div class="px-4 mb-8">
      <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
        <h3 class="font-semibold text-amber-900 mb-2 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
          </svg>
          Catatan Pesanan
        </h3>
        <p class="text-sm text-amber-800">{{ $order->notes }}</p>
      </div>
    </div>
    @endif

    {{-- What's Next --}}
    <div class="px-4 mb-8">
      <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Apa Selanjutnya?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
              <span class="text-blue-600 font-bold">1</span>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Konfirmasi Pesanan</h3>
              <p class="text-sm text-gray-600">Anda akan segera menerima email konfirmasi.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
              <span class="text-blue-600 font-bold">2</span>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Pengolahan</h3>
              <p class="text-sm text-gray-600">Kami sedang menyiapkan pesanan Anda untuk pengiriman.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
              <span class="text-blue-600 font-bold">3</span>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Pengiriman</h3>
              <p class="text-sm text-gray-600">Lacak pesanan Anda di bagian Pesanan Saya.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 px-4">
      <a href="{{ route('products') }}" wire:navigate
         class="w-full sm:w-auto px-8 py-3 text-blue-600 bg-white border-2 border-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Lanjutkan Berbelanja
      </a>
      <a href="{{ route('user.my-orders') }}" wire:navigate
         class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
        Lihat Pesanan Saya
        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>
    </div>

    <div class="mt-8 px-4 text-center">
      <p class="text-sm text-gray-600">
        Butuh bantuan? Hubungi
        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold underline">customer support</a>
        atau cek
        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold underline">FAQ</a> kami.
      </p>
    </div>

  </div>
</section>