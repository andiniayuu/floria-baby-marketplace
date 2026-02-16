<div class="min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50 py-8 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-4">
      <a href="/my-orders" class="inline-flex items-center text-slate-600 hover:text-slate-800 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Orders
      </a>
    </div>

    <div class="mb-6">
      <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800 mb-2">
        Order Details
      </h1>
      <p class="text-slate-600">Order #{{ $order->id }} • {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>

    <!-- Status Cards Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
      
      <!-- Customer Card -->
      <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg">
            <svg class="size-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>

          <div class="grow">
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">
              Customer
            </p>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">
              {{ $order->user->name ?? 'N/A' }}
            </h3>
          </div>
        </div>
      </div>

      <!-- Order Date Card -->
      <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg">
            <svg class="size-5 text-purple-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>

          <div class="grow">
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">
              Order Date
            </p>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800">
              {{ $order->created_at->format('d M Y') }}
            </h3>
          </div>
        </div>
      </div>

      <!-- Order Status Card -->
      <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-green-100 to-green-200 rounded-lg">
            <svg class="size-5 text-green-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
              <path d="m12 12 4 10 1.7-4.3L22 16Z" />
            </svg>
          </div>

          <div class="grow">
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">
              Order Status
            </p>
            @php
              $status = '';
              if($order->status == 'new'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-sm">New</span>';
              }
              if($order->status == 'processing'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white shadow-sm">Processing</span>';
              }
              if($order->status == 'shipped'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-sm">Shipped</span>';
              }
              if($order->status == 'delivered'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-700 text-white shadow-sm">Delivered</span>';
              }
              if($order->status == 'cancelled'){
                $status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-700 text-white shadow-sm">Cancelled</span>';
              }
            @endphp
            {!! $status !!}
          </div>
        </div>
      </div>

      <!-- Payment Status Card -->
      <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md transition-shadow">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg">
            <svg class="size-5 text-orange-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
              <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
          </div>

          <div class="grow">
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">
              Payment Status
            </p>
            @php
              $payment_status = '';
              if($order->payment_status == 'pending'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-sm">Pending</span>';
              }
              if($order->payment_status == 'paid'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white shadow-sm">Paid</span>';
              }
              if($order->payment_status == 'failed'){
                $payment_status = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white shadow-sm">Failed</span>';
              }
            @endphp
            {!! $payment_status !!}
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
              Order Items
            </h2>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="text-left font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3">Product</th>
                  <th class="hidden sm:table-cell text-right font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[120px]">Price</th>
                  <th class="hidden sm:table-cell text-center font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[100px]">Quantity</th>
                  <th class="text-right font-semibold text-xs uppercase tracking-wider text-gray-600 px-6 py-3 w-[120px]">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @foreach ($order->items as $item)
                <tr wire:key="{{ $item->id }}" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <img class="h-14 w-14 sm:h-16 sm:w-16 object-cover rounded-lg border border-gray-200" 
                           src="{{ url('storage', $item->product->images[0]) }}" 
                           alt="{{ $item->product->name }}">
                      <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm sm:text-base text-gray-800 line-clamp-2">
                          {{ $item->product->name }}
                        </p>
                        <!-- Mobile: Show price and qty -->
                        <div class="sm:hidden text-xs text-gray-500 mt-1 space-y-1">
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

        <!-- Manual Bank Transfer Section -->
        @if($order->payment_method == 'manual_transfer')
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
          <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-200">
            <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
              Bank Transfer Information
            </h2>
          </div>
          
          <div class="p-6">
            @if($order->payment_status == 'pending')
            <!-- Pending Payment - Show Bank Details -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                  <h3 class="font-semibold text-yellow-800 mb-1">Awaiting Payment</h3>
                  <p class="text-sm text-yellow-700">Please transfer to one of our bank accounts below</p>
                </div>
              </div>
            </div>

            <!-- Bank Account Details -->
            <div class="space-y-4">
              <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Bank BCA</p>
                    <p class="text-lg font-bold text-gray-900">1234567890</p>
                  </div>
                </div>
                <div class="pt-3 border-t border-gray-200">
                  <p class="text-sm text-gray-600">Account Name: <span class="font-semibold text-gray-900">PT Toko Online Indonesia</span></p>
                </div>
              </div>

              <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Bank Mandiri</p>
                    <p class="text-lg font-bold text-gray-900">9876543210</p>
                  </div>
                </div>
                <div class="pt-3 border-t border-gray-200">
                  <p class="text-sm text-gray-600">Account Name: <span class="font-semibold text-gray-900">PT Toko Online Indonesia</span></p>
                </div>
              </div>

              <!-- Transfer Amount Highlight -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-lg p-4">
                <div class="text-center">
                  <p class="text-sm text-gray-600 mb-2">Total Transfer Amount</p>
                  <p class="text-3xl font-bold text-blue-700">{{ Number::currency($order->grand_total, 'IDR') }}</p>
                  <p class="text-xs text-gray-500 mt-2">Please transfer the exact amount including unique code if any</p>
                </div>
              </div>

              <!-- Upload Payment Proof Form -->
              <div class="border-t-2 border-gray-200 pt-4 mt-4">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Upload Payment Proof
                </h3>
                
                <form wire:submit.prevent="uploadPaymentProof" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Transfer Receipt / Screenshot
                    </label>
                    <div class="flex items-center justify-center w-full">
                      <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                          <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                          </svg>
                          <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                          <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                        </div>
                        <input type="file" wire:model="payment_proof" accept="image/*" class="hidden" />
                      </label>
                    </div>
                    @error('payment_proof') 
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Transfer Date
                    </label>
                    <input type="date" wire:model="transfer_date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('transfer_date') 
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Notes (Optional)
                    </label>
                    <textarea wire:model="payment_notes" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="Add any notes about your transfer..."></textarea>
                  </div>

                  <button type="submit" 
                          class="w-full inline-flex items-center justify-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Submit Payment Proof
                  </button>
                </form>
              </div>
            </div>

            @elseif($order->payment_status == 'paid' && $order->payment_proof)
            <!-- Payment Verified - Show Uploaded Proof -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                  <h3 class="font-semibold text-green-800 mb-1">Payment Verified</h3>
                  <p class="text-sm text-green-700">Your payment has been confirmed</p>
                </div>
              </div>
            </div>

            <!-- Display Payment Proof -->
            <div class="space-y-4">
              <div>
                <h3 class="font-semibold text-gray-700 mb-2">Payment Receipt</h3>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                  <img src="{{ url('storage', $order->payment_proof) }}" 
                       alt="Payment Proof" 
                       class="w-full h-auto max-h-96 object-contain bg-gray-50">
                </div>
              </div>
              
              @if($order->transfer_date)
              <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Transfer Date: <span class="font-medium text-gray-800">{{ $order->transfer_date }}</span>
              </div>
              @endif

              @if($order->payment_notes)
              <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-500 font-medium mb-1">Transfer Notes:</p>
                <p class="text-sm text-gray-700">{{ $order->payment_notes }}</p>
              </div>
              @endif
            </div>
            @endif
          </div>
        </div>
        @endif

        <!-- Shipping Address -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
          <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Shipping Address
            </h2>
          </div>
          
          <div class="p-6 space-y-4">
            @php
              $recipient = $order->recipient_info;
            @endphp
            
            <!-- Recipient Info -->
            <div class="pb-4 border-b border-gray-200">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">{{ $recipient['name'] }}</h3>
                  @if($order->address && $order->address->label)
                  <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded mt-1">
                    {{ $order->address->label }}
                  </span>
                  @endif
                </div>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-600 ml-13">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span class="font-medium">{{ $recipient['phone'] }}</span>
              </div>
            </div>

            <!-- Full Address -->
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Delivery Address</p>
                <p class="text-sm text-gray-700 leading-relaxed">
                  {{ $order->full_shipping_address }}
                </p>
              </div>
            </div>

            <!-- Shipping Method -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
              <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-800">
                  {{ ucfirst($order->shipping_method ?? 'Standard') }} Shipping
                </p>
                <p class="text-sm text-gray-600">
                  {{ Number::currency($order->shipping_amount ?? 0, 'IDR') }}
                </p>
              </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
            <div class="pt-4 border-t border-gray-200">
              <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                <div class="flex items-start gap-2">
                  <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/>
                  </svg>
                  <div>
                    <p class="text-xs text-amber-600 font-semibold mb-1">Order Notes:</p>
                    <p class="text-sm text-amber-800">{{ $order->notes }}</p>
                  </div>
                </div>
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
              Order Summary
            </h2>
          </div>
          
          <div class="p-6 space-y-4">
            @php
              $subtotal = $order->items->sum('total_amount');
            @endphp
            
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-medium text-gray-900">{{ Number::currency($subtotal, 'IDR') }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Shipping</span>
              <span class="font-medium text-gray-900">{{ Number::currency($order->shipping_amount ?? 0, 'IDR') }}</span>
            </div>
            
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-600">Tax</span>
              <span class="font-medium text-gray-900">{{ Number::currency(0, 'IDR') }}</span>
            </div>
            
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
                  <p class="text-xs text-gray-500 font-medium mb-1">Payment Method</p>
                  <p class="text-sm font-semibold text-gray-800">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            @if($order->status != 'cancelled')
            <div class="pt-4 border-t border-gray-200 space-y-3">
              @if($order->status == 'delivered')
              <button class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Order Again
              </button>
              @endif

              <button class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg bg-white border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Contact Support
              </button>
            </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>
</div>