<div class="pt-20 min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-2">Pesanan Saya</h1>
      <p class="text-slate-600">Lacak dan kelola riwayat pesanan Anda</p>
    </div>

    <!-- Orders Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

      <!-- Desktop Table View -->
      <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">ID Pesanan</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status Pesanan</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status Pembayaran</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            @forelse ($orders as $order)
            @php
              // =============================================
              // STATUS PESANAN
              // =============================================
              $statusBadge = match($order->status) {
                'pending'    => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                  Menunggu Konfirmasi
                                </span>',
                'new'        => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/></svg>
                                  Pesanan Baru
                                </span>',
                'processing' => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                  <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                  Sedang Dikemas
                                </span>',
                'shipped'    => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                                  Sedang Dikirim
                                </span>',
                'delivered'  => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                  Pesanan Tiba
                                </span>',
                'cancelled'  => '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                  Dibatalkan
                                </span>',
                default      => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">'.ucfirst($order->status).'</span>',
              };

              // =============================================
              // STATUS PEMBAYARAN — gunakan isPaid() dari model
              // =============================================
              $isPaid = $order->isPaid();

              if ($isPaid) {
                $paymentBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                  Lunas
                                </span>';
              } elseif ($order->payment_method === 'cod') {
                $paymentBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                                  Bayar di Tempat (COD)
                                </span>';
              } elseif ($order->payment_status === 'failed') {
                $paymentBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                  Pembayaran Gagal
                                </span>';
              } else {
                $paymentBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                  Menunggu Pembayaran
                                </span>';
              }
            @endphp

            <tr class="hover:bg-slate-50 transition-colors duration-150" wire:key="desktop-{{ $order->id }}">
              <td class="px-6 py-4">
                <span class="text-sm font-bold text-slate-800">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-slate-600">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                <p class="text-xs text-slate-400">{{ $order->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
              </td>
              <td class="px-6 py-4">{!! $statusBadge !!}</td>
              <td class="px-6 py-4">{!! $paymentBadge !!}</td>
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-slate-800">{{ Number::currency($order->grand_total, 'IDR') }}</span>
              </td>
              <td class="px-6 py-4 text-center">
                <a href="{{ route('user.my-orders.show', $order->id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-700 text-white text-sm font-medium hover:bg-slate-600 transition-all duration-200 shadow-sm hover:shadow gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Lihat Detail
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-16 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-600 mb-2">Belum Ada Pesanan</h3>
                <p class="text-slate-500 mb-6 text-sm">Mulailah berbelanja untuk melihat pesanan Anda di sini.</p>
                <a href="{{ route('products') }}" wire:navigate
                   class="inline-flex items-center px-6 py-3 rounded-lg bg-pink-500 text-white font-medium hover:bg-pink-600 transition-colors text-sm">
                  Mulai Berbelanja
                </a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Mobile Card View -->
      <div class="lg:hidden divide-y divide-gray-200">
        @forelse ($orders as $order)
        @php
          // Status order (mobile)
          $mobileStatus = match($order->status) {
            'pending'    => ['class' => 'bg-gray-100 text-gray-700',   'text' => 'Menunggu Konfirmasi'],
            'new'        => ['class' => 'bg-blue-100 text-blue-700',   'text' => 'Pesanan Baru'],
            'processing' => ['class' => 'bg-yellow-100 text-yellow-700', 'text' => 'Sedang Dikemas'],
            'shipped'    => ['class' => 'bg-purple-100 text-purple-700', 'text' => 'Sedang Dikirim'],
            'delivered'  => ['class' => 'bg-green-100 text-green-700', 'text' => 'Pesanan Tiba'],
            'cancelled'  => ['class' => 'bg-red-100 text-red-700',     'text' => 'Dibatalkan'],
            default      => ['class' => 'bg-gray-100 text-gray-600',   'text' => ucfirst($order->status)],
          };

          // Status pembayaran (mobile) — gunakan isPaid()
          $isPaidMobile = $order->isPaid();
          if ($isPaidMobile) {
            $mobilePayment = ['class' => 'bg-green-100 text-green-700', 'text' => 'Lunas'];
          } elseif ($order->payment_method === 'cod') {
            $mobilePayment = ['class' => 'bg-amber-100 text-amber-700', 'text' => 'Bayar di Tempat (COD)'];
          } elseif ($order->payment_status === 'failed') {
            $mobilePayment = ['class' => 'bg-red-100 text-red-700', 'text' => 'Pembayaran Gagal'];
          } else {
            $mobilePayment = ['class' => 'bg-orange-100 text-orange-700', 'text' => 'Menunggu Pembayaran'];
          }
        @endphp

        <div class="p-5 hover:bg-slate-50 transition-colors" wire:key="mobile-{{ $order->id }}">
          <div class="flex justify-between items-start mb-3">
            <div>
              <p class="text-xs text-slate-500 mb-1">ID Pesanan</p>
              <p class="text-base font-bold text-slate-800">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
              <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $mobileStatus['class'] }}">
              {{ $mobileStatus['text'] }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
              <p class="text-xs text-slate-500 mb-1">Status Pembayaran</p>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $mobilePayment['class'] }}">
                {{ $mobilePayment['text'] }}
              </span>
            </div>
            <div>
              <p class="text-xs text-slate-500 mb-1">Total</p>
              <p class="text-sm font-bold text-slate-800">{{ Number::currency($order->grand_total, 'IDR') }}</p>
            </div>
          </div>

          <div class="pt-3 border-t border-gray-200">
            <a href="{{ route('user.my-orders.show', $order->id) }}"
               class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-slate-700 text-white text-sm font-medium hover:bg-slate-600 transition-all duration-200 gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Lihat Detail Pesanan
            </a>
          </div>
        </div>
        @empty
        <div class="text-center py-16 px-4">
          <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <h3 class="text-lg font-semibold text-slate-600 mb-2">Belum Ada Pesanan</h3>
          <p class="text-slate-500 mb-6 text-sm">Mulailah berbelanja untuk melihat pesanan Anda di sini.</p>
          <a href="{{ route('products') }}" wire:navigate
             class="inline-flex items-center px-6 py-3 rounded-lg bg-pink-500 text-white font-medium hover:bg-pink-600 transition-colors text-sm">
            Mulai Berbelanja
          </a>
        </div>
        @endforelse
      </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $orders->links() }}
    </div>

  </div>
</div>