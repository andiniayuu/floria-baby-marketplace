<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-4xl font-bold text-slate-700">My Orders</h1>

  <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg border border-gray-200">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
              <tr>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Order</th>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Order Status</th>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Payment Status</th>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Order Amount</th>
                <th class="px-6 py-4 text-start text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              @foreach ($orders as $order)
              @php
                $status = '';
                $payment_status = '';

                if($order->status == 'new'){
                  $status = '<span class="bg-blue-100 text-blue-700 py-1 px-3 rounded text-xs font-semibold">New</span>';
                }
                if($order->status == 'processing'){
                  $status = '<span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded text-xs font-semibold">Processing</span>';
                }
                if($order->status == 'shipped'){
                  $status = '<span class="bg-green-100 text-green-700 py-1 px-3 rounded text-xs font-semibold">Shipped</span>';
                }
                if($order->status == 'delivered'){
                  $status = '<span class="bg-green-200 text-green-800 py-1 px-3 rounded text-xs font-semibold">Delivered</span>';
                }
                if($order->status == 'cancelled'){
                  $status = '<span class="bg-red-100 text-red-700 py-1 px-3 rounded text-xs font-semibold">Cancelled</span>';
                }

                if($order->payment_status == 'pending'){
                  $payment_status = '<span class="bg-blue-100 text-blue-700 py-1 px-3 rounded text-xs font-semibold">Pending</span>';
                }
                if($order->payment_status == 'paid'){
                  $payment_status = '<span class="bg-green-100 text-green-700 py-1 px-3 rounded text-xs font-semibold">Paid</span>';
                }
                if($order->payment_status == 'failed'){
                  $payment_status = '<span class="bg-red-100 text-red-700 py-1 px-3 rounded text-xs font-semibold">Failed</span>';
                }
              @endphp

              <tr class="odd:bg-white even:bg-gray-50" wire:key="{{ $order->id }}">
                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                  {{ $order->id }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ $order->created_at->format('d-m-y') }}
                </td>
                <td class="px-6 py-4 text-sm">
                  {!! $status !!}
                </td>
                <td class="px-6 py-4 text-sm">
                  {!! $payment_status !!}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ Number::currency($order->grand_total, 'IDR') }}
                </td>
                <td class="px-6 py-4 text-end text-sm font-medium">
                  <a href="/my-orders/{{ $order->id }}"
                     class="inline-flex items-center px-4 py-2 rounded-md bg-slate-600 text-white hover:bg-slate-500 transition">
                    View Details
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>

          </table>
        </div>
      </div>
       {{ $orders->links() }}
    </div>
  </div>
</div>
