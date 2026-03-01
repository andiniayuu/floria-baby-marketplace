<x-layouts.app>

@section('title', 'Ajukan Menjadi Seller')

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
            <h1 class="text-3xl font-bold text-gray-900">Ajukan Menjadi Seller</h1>
            <p class="text-gray-600 mt-2">Lengkapi formulir di bawah untuk mulai berjualan di platform kami</p>
        </div>

        {{-- Info Banner --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Keuntungan Menjadi Seller:</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li>Gratis mendaftar dan setup toko</li>
                        <li>Dukungan tim customer service</li>
                        <li>Proses verifikasi cepat 1-3 hari kerja</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.seller.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Informasi Toko --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Toko</h2>

                <div class="space-y-4">
                    {{-- Store Name --}}
                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Toko <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="store_name" 
                               id="store_name" 
                               value="{{ old('store_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_name') border-red-500 @enderror"
                               placeholder="Contoh: Toko Elektronik Sejahtera"
                               required>
                        @error('store_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nama toko harus unik dan belum digunakan</p>
                    </div>

                    {{-- Store Description --}}
                    <div>
                        <label for="store_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi Toko <span class="text-red-500">*</span>
                        </label>
                        <textarea name="store_description" 
                                  id="store_description"
                                  minlength="50"
                                  maxlength="1000"  
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_description') border-red-500 @enderror"
                                  placeholder="Ceritakan tentang toko Anda, produk yang dijual, dan keunggulan toko Anda... (minimal 50 karakter)"
                                  required>{{ old('store_description') }}</textarea>
                        @error('store_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" id="char-count">(minimal 50 karakter)</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Pemilik --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pemilik</h2>

                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nama Lengkap</span>
                        <span class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email</span>
                        <span class="text-sm font-medium text-gray-900">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Syarat & Ketentuan --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Syarat dan Ketentuan</h2>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4 max-h-48 overflow-y-auto text-sm text-gray-700 space-y-2">
                    <p class="font-semibold">Dengan menjadi seller, Anda menyetujui:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Menjual produk yang legal dan sesuai dengan hukum yang berlaku</li>
                        <li>Memberikan deskripsi produk yang jujur dan akurat</li>
                        <li>Memproses pesanan dengan cepat dan profesional</li>
                        <li>Menjaga kualitas layanan pelanggan yang baik</li>
                        <li>Membayar komisi platform sesuai kesepakatan</li>
                        <li>Mematuhi kebijakan dan aturan platform</li>
                    </ul>
                </div>

                <div class="flex items-start">
                    <input type="checkbox" 
                           name="agree_terms" 
                           id="agree_terms" 
                           value="1"
                           class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                           required>
                    <label for="agree_terms" class="ml-3 text-sm text-gray-700">
                        Saya telah membaca dan menyetujui <strong>Syarat dan Ketentuan</strong> di atas
                        <span class="text-red-500">*</span>
                    </label>
                </div>
                @error('agree_terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-150">
                    Kirim Pengajuan
                </button>
                <a href="{{ route('user.profile.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition duration-150">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
// Character counter
const textarea = document.getElementById('store_description');
const charCount = document.getElementById('char-count');

textarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = `${length}/1000 karakter (minimal 50)`;
    
    if (length < 50) {
        charCount.classList.add('text-red-600');
    } else {
        charCount.classList.remove('text-red-600');
        charCount.classList.add('text-green-600');
    }
});
</script>
@endpush

</x-layouts.app>