<section class="min-h-screen flex items-center font-poppins bg-gradient-to-br from-gray-50 to-red-50 pt-20 md:pt-28 pb-12">
  <div class="justify-center flex-1 max-w-3xl px-4 py-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
    
    {{-- Cancel Header --}}
    <div class="text-center mb-8">
      <div class="flex justify-center mb-4">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
          <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
      </div>
      
      <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
        Payment Cancelled
      </h1>
      <p class="text-gray-600 text-lg mb-6">
        Your order was not completed. No charges have been made.
      </p>
    </div>

    {{-- Info Box --}}
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="font-semibold text-amber-900 mb-2">What happened?</h3>
          <p class="text-sm text-amber-800 mb-2">
            You cancelled the payment process or the payment session expired.
          </p>
          <p class="text-sm text-amber-800">
            Don't worry, your items are still in your cart and no payment was processed.
          </p>
        </div>
      </div>
    </div>

    {{-- Options --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
      <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 mb-1">Return to Cart</h3>
            <p class="text-sm text-gray-600 mb-3">Review your items and try again</p>
            <a href="{{ route('cart') }}" 
               wire:navigate
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
              Go to Cart
            </a>
          </div>
        </div>
      </div>

      <div class="bg-green-50 rounded-lg p-6 border border-green-200">
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 mb-1">Continue Shopping</h3>
            <p class="text-sm text-gray-600 mb-3">Browse more products</p>
            <a href="{{ route('products') }}" 
               wire:navigate
               class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition">
              Browse Products
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Why it might have failed --}}
    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
      <h3 class="font-semibold text-gray-900 mb-4">Common reasons for payment cancellation:</h3>
      <ul class="space-y-2 text-sm text-gray-700">
        <li class="flex items-start gap-2">
          <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span>You clicked the "Cancel" or "Back" button</span>
        </li>
        <li class="flex items-start gap-2">
          <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span>The payment session timed out</span>
        </li>
        <li class="flex items-start gap-2">
          <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span>You decided to use a different payment method</span>
        </li>
      </ul>
    </div>

    {{-- Help Section --}}
    <div class="mt-8 text-center">
      <p class="text-sm text-gray-600">
        Need help? 
        <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold underline">Contact Support</a>
      </p>
    </div>

  </div>
</section>