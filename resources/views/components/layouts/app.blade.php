<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'BabyShop' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-200 dark:bg-slate-700">
        @livewire('partials.navbar')
        <main>{{ $slot }}</main>
        @livewire('partials.footer')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

@livewireScripts

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('livewire:initialized', () => {

        Livewire.on('open-midtrans-popup', ({ token, orderId }) => {
            console.log('🎯 Event diterima, token:', token?.substring(0, 20));

            if (!token) {
                console.error('❌ Token kosong!');
                return;
            }

            if (typeof window.snap === 'undefined') {
                console.error('❌ snap.js belum load!');
                alert('Payment gateway belum siap. Refresh halaman.');
                return;
            }

            window.snap.pay(token, {
                onSuccess: (result) => {
                    window.location.href = `/user/payment/${orderId}/finish?status=success`;
                },
                onPending: (result) => {
                    window.location.href = `/user/payment/${orderId}/finish?status=pending`;
                },
                onError: (result) => {
                    console.error('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: () => {
                    console.log('Popup ditutup oleh user');
                }
            });
        });

    });
</script>

    @stack('scripts')

        <!-- Scroll to Top Button -->
        <div x-data="{ scrollTop: false }"
             x-init="window.addEventListener('scroll', () => { scrollTop = window.pageYOffset > 300 })"
             x-show="scrollTop"
             x-transition
             class="fixed bottom-8 right-8 z-40">
            <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="bg-pink-500 hover:bg-pink-600 text-white p-3 rounded-full shadow-lg transition duration-300 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </button>
        </div>

    </body>
</html>