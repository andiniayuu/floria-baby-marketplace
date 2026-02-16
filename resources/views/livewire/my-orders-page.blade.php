<div class="pt-20 min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-2">My Orders</h1>
      <p class="text-slate-600">Lacak dan kelola riwayat pesanan Anda</p>
    </div>

    <!-- Orders Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
      
      <!-- Desktop Table View -->
      <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                ID Pesanan
              </th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                Tanggal
              </th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                Status Order
              </th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                Pembayaran
              </th>
              <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                Jumlah
              </th>
              <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            @foreach ($orders as $order)
            @php
              $status = '';
              $payment_status = '';

              if($order->status == 'new'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                  </svg>
                  New
                </span>';
              }
              if($order->status == 'processing'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                  <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Processing
                </span>';
              }
              if($order->status == 'shipped'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                  </svg>
                  Shipped
                </span>';
              }
              if($order->status == 'delivered'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  Delivered
                </span>';
              }
              if($order->status == 'cancelled'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                  </svg>
                  Cancelled
                </span>';
              }

              if($order->payment_status == 'pending'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Pending</span>';
              }
              if($order->payment_status == 'paid'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Paid</span>';
              }
              if($order->payment_status == 'failed'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Failed</span>';
              }
            @endphp

            <tr class="hover:bg-slate-50 transition-colors duration-150" wire:key="{{ $order->id }}">
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-slate-800">{{ $order->id }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-slate-700">{{ $order->created_at->format('d M Y') }}</span>
              </td>
              <td class="px-6 py-4">
                {!! $status !!}
              </td>
              <td class="px-6 py-4">
                {!! $payment_status !!}
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-slate-800">{{ Number::currency($order->grand_total, 'IDR') }}</span>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('user.my-orders.show', $order->id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-700 text-white text-sm font-medium hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 shadow-sm hover:shadow">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Lihat Detail
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Mobile Card View -->
      <div class="lg:hidden divide-y divide-gray-200">
        @foreach ($orders as $order)
        @php
          $status_class = '';
          $status_text = '';
          $status_icon = '';
          
          if($order->status == 'new'){
            $status_class = 'bg-blue-100 text-blue-700';
            $status_text = 'New';
            $status_icon = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/></svg>';
          }
          if($order->status == 'processing'){
            $status_class = 'bg-yellow-100 text-yellow-700';
            $status_text = 'Processing';
            $status_icon = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
          }
          if($order->status == 'shipped'){
            $status_class = 'bg-green-100 text-green-700';
            $status_text = 'Shipped';
            $status_icon = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>';
          }
          if($order->status == 'delivered'){
            $status_class = 'bg-green-200 text-green-800';
            $status_text = 'Delivered';
            $status_icon = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
          }
          if($order->status == 'cancelled'){
            $status_class = 'bg-red-100 text-red-700';
            $status_text = 'Cancelled';
            $status_icon = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
          }

          $payment_class = '';
          $payment_text = '';
          
          if($order->payment_status == 'pending'){
            $payment_class = 'bg-blue-100 text-blue-700';
            $payment_text = 'Pending';
          }
          if($order->payment_status == 'paid'){
            $payment_class = 'bg-green-100 text-green-700';
            $payment_text = 'Paid';
          }
          if($order->payment_status == 'failed'){
            $payment_class = 'bg-red-100 text-red-700';
            $payment_text = 'Failed';
          }
        @endphp

        <div class="p-5 hover:bg-slate-50 transition-colors" wire:key="{{ $order->id }}">
          <div class="flex justify-between items-start mb-3">
            <div>
              <p class="text-xs text-slate-500 mb-1">ID Order</p>
              <p class="text-base font-semibold text-slate-800">#{{ $order->id }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $status_class }}">
              {!! $status_icon !!}
              <span class="ml-1">{{ $status_text }}</span>
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <p class="text-xs text-slate-500 mb-1">Tanggal</p>
              <p class="text-sm text-slate-700 font-medium">{{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500 mb-1">Pembayaran</p>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $payment_class }}">
                {{ $payment_text }}
              </span>
            </div>
          </div>

          <div class="flex justify-between items-center pt-3 border-t border-gray-200">
            <div>
              <p class="text-xs text-slate-500 mb-1">Jumlah Total</p>
              <p class="text-base font-bold text-slate-800">{{ Number::currency($order->grand_total, 'IDR') }}</p>
            </div>
            <a href="{{ route('user.my-orders.show', $order->id) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-700 text-white text-sm font-medium hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 shadow-sm">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Detail
            </a>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Empty State -->
      @if($orders->isEmpty())
      <div class="text-center py-16 px-4">
        <svg class="mx-auto h-16 w-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum Ada Pesanan</h3>
        <p class="text-slate-500 mb-6">Mulailah berbelanja untuk melihat pesanan Anda di sini.</p>
        <a href="/" class="inline-flex items-center px-6 py-3 rounded-lg bg-slate-700 text-white font-medium hover:bg-slate-600 transition-colors">
          Mulai Berbelanja
        </a>
      </div>
      @endif
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $orders->links() }}
    </div>
  </div>
</div>