<x-layouts.app>

@section('title', 'Tambah Alamat Baru')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('user.addresses.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Alamat
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Alamat Baru</h1>
        </div>
        <input type="hidden" name="from" value="{{ request('from') }}">


        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('user.addresses.store') }}" method="POST">
                @csrf

                {{-- Label Alamat --}}
                <div class="mb-6">
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-2">
                        Label Alamat (Opsional)
                    </label>
                    <input type="text" 
                           name="label" 
                           id="label" 
                           value="{{ old('label') }}"
                           placeholder="Contoh: Rumah, Kantor, Kos"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('label') border-red-500 @enderror">
                    @error('label')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Penerima --}}
                <div class="mb-6">
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="recipient_name" 
                           id="recipient_name" 
                           value="{{ old('recipient_name') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('recipient_name') border-red-500 @enderror">
                    @error('recipient_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grid 2 Kolom --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    
                    {{-- Provinsi --}}
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="province" 
                               id="province" 
                               value="{{ old('province') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('province') border-red-500 @enderror">
                        @error('province')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kota/Kabupaten --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota/Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="city" 
                               id="city" 
                               value="{{ old('city') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                        @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan
                        </label>
                        <input type="text" 
                               name="district" 
                               id="district" 
                               value="{{ old('district') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('district') border-red-500 @enderror">
                        @error('district')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kelurahan --}}
                    <div>
                        <label for="subdistrict" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelurahan
                        </label>
                        <input type="text" 
                               name="subdistrict" 
                               id="subdistrict" 
                               value="{{ old('subdistrict') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subdistrict') border-red-500 @enderror">
                        @error('subdistrict')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Kode Pos --}}
                <div class="mb-6">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pos <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="postal_code" 
                           id="postal_code" 
                           value="{{ old('postal_code') }}"
                           placeholder="12345"
                           required
                           maxlength="5"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                    @error('postal_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Lengkap --}}
                <div class="mb-6">
                    <label for="full_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="full_address" 
                              id="full_address" 
                              rows="3" 
                              required
                              placeholder="Nama jalan, nomor rumah, RT/RW, dll."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('full_address') border-red-500 @enderror">{{ old('full_address') }}</textarea>
                    @error('full_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2"
                              placeholder="Contoh: Rumah pagar hijau, sebelah minimarket"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Set as Primary --}}
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_primary" 
                               value="1"
                               {{ old('is_primary') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Jadikan sebagai alamat utama</span>
                    </label>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                        Simpan Alamat
                    </button>
                    <a href="{{ route('user.addresses.index') }}" 
                       class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-150">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>
</x-layouts.app>