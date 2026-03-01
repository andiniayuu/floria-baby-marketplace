<x-layouts.app>

@section('title', 'Status Pengajuan Seller')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 pt-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('user.profile.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Profil
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Status Pengajuan Seller</h1>
        </div>

        {{-- Status Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            
            {{-- Status Header --}}
            <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $sellerRequest->store_name }}</h2>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $sellerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                               ($sellerRequest->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ $sellerRequest->status === 'pending' ? 'Menunggu' : 
                               ($sellerRequest->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                        </span>
                    </div>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Diajukan pada {{ $sellerRequest->created_at->format('d M Y, H:i') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">No. Pengajuan: {{ $sellerRequest->request_number }}</p>
                </div>
            </div>

            {{-- Status Content Based on Status --}}
            @if($sellerRequest->status === 'pending')
                {{-- Pending Status --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex">
                        <svg class="w-8 h-8 text-yellow-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Pengajuan Sedang Ditinjau</h3>
                            <p class="text-yellow-800 mb-4">
                                Tim kami sedang meninjau pengajuan Anda. Proses verifikasi biasanya memakan waktu 1-3 hari kerja. 
                                Kami akan mengirimkan notifikasi melalui email setelah pengajuan Anda diproses.
                            </p>
                            <div class="bg-white rounded-lg p-4 border border-yellow-300">
                                <p class="text-sm font-medium text-gray-900 mb-3">Tahapan Review:</p>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Pengajuan Diterima</p>
                                            <p class="text-xs text-gray-600">{{ $sellerRequest->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Proses Verifikasi</p>
                                            <p class="text-xs text-gray-600">Sedang ditinjau oleh tim kami</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start opacity-50">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Keputusan</p>
                                            <p class="text-xs text-gray-600">Menunggu hasil review</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($sellerRequest->status === 'approved')
                {{-- Approved Status --}}
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex">
                        <svg class="w-8 h-8 text-green-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-900 mb-2">🎉 Selamat! Pengajuan Anda Disetujui</h3>
                            <p class="text-green-800 mb-4">
                                Pengajuan seller Anda telah disetujui pada {{ $sellerRequest->reviewed_at?->format('d M Y, H:i') }}. 
                                Sekarang Anda dapat mulai berjualan di platform kami!
                            </p>
                            
                            <div class="bg-white rounded-lg p-4 border border-green-300 mb-4">
                                <p class="text-sm font-medium text-gray-900 mb-2">Langkah Selanjutnya:</p>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Buka dashboard seller untuk mulai mengelola toko
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Tambahkan produk pertama Anda
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Atur metode pengiriman dan pembayaran
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ filament()->getPanel('seller')->getUrl() }}"
                               class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Buka Dashboard Seller
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($sellerRequest->status === 'rejected')
                {{-- Rejected Status --}}
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex">
                        <svg class="w-8 h-8 text-red-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Pengajuan Ditolak</h3>
                            <p class="text-red-800 mb-4">
                                Mohon maaf, pengajuan seller Anda ditolak pada {{ $sellerRequest->reviewed_at?->format('d M Y, H:i') }}.
                            </p>
                            
                            @if($sellerRequest->rejection_reason)
                            <div class="bg-white rounded-lg p-4 border border-red-300 mb-4">
                                <p class="text-sm font-medium text-gray-900 mb-1">Alasan Penolakan:</p>
                                <p class="text-sm text-gray-700">{{ $sellerRequest->rejection_reason }}</p>
                            </div>
                            @endif

                            <div class="bg-white rounded-lg p-4 border border-red-300 mb-4">
                                <p class="text-sm font-medium text-gray-900 mb-2">Anda dapat mengajukan kembali dengan:</p>
                                <ul class="space-y-1 text-sm text-gray-700 list-disc list-inside">
                                    <li>Memperbaiki informasi toko sesuai alasan penolakan</li>
                                    <li>Memastikan deskripsi toko lebih detail dan jelas</li>
                                    <li>Melengkapi data yang mungkin kurang</li>
                                </ul>
                            </div>

                            <a href="{{ route('user.seller.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Ajukan Kembali
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Detail Information --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan</h3>
            
            <div class="space-y-4">
                {{-- Store Info --}}
                <div>
                    <p class="text-sm text-gray-600">Nama Toko</p>
                    <p class="font-medium text-gray-900">{{ $sellerRequest->store_name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Deskripsi Toko</p>
                    <p class="text-gray-900">{{ $sellerRequest->store_description }}</p>
                </div>

                {{-- Owner Info --}}
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Pemilik</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Lengkap</p>
                            <p class="font-medium text-gray-900">{{ $sellerRequest->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $sellerRequest->user->email }}</p>
                        </div>
                        @if($sellerRequest->user->phone)
                        <div>
                            <p class="text-sm text-gray-600">Nomor Telepon</p>
                            <p class="font-medium text-gray-900">{{ $sellerRequest->user->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Button (Only for Pending) --}}
        @if($sellerRequest->status === 'pending')
        <div class="mt-6">
            <form action="{{ route('user.seller.cancel') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full md:w-auto px-6 py-3 border border-red-300 rounded-lg text-red-700 hover:bg-red-50 font-semibold transition duration-150 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Pengajuan
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

</x-layouts.app>