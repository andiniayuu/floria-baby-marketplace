<x-layouts.app>

@section('title', 'Edit Profil')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <p class="text-gray-600">Perbarui informasi profil Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            {{-- Sidebar Menu --}}
            <div class="lg:col-span-1">
                @include('user.profile.partials.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                
                {{-- Success Message --}}
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                {{-- Error Message --}}
                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
                @endif

                {{-- ============================================ --}}
                {{-- FORM HAPUS AVATAR (pisah dari form utama!) --}}
                {{-- ============================================ --}}
                @if($user->avatar)
                <form id="form-delete-avatar" 
                      action="{{ route('user.profile.delete-avatar') }}" 
                      method="POST" 
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endif

                {{-- Form Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    
                    {{-- ============================================ --}}
                    {{-- FORM UTAMA UPDATE PROFIL --}}
                    {{-- ============================================ --}}
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar Section --}}
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Profil</h3>
                            
                            <div class="flex items-start gap-6">
                                {{-- Current Avatar --}}
                                <div class="flex-shrink-0">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" 
                                             alt="{{ $user->name }}" 
                                             id="avatar-preview"
                                             class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                                    @else
                                        <div id="avatar-preview" 
                                             class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center border-4 border-gray-200">
                                            <span class="text-4xl font-bold text-white">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Upload Controls --}}
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Ganti Foto Profil
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <label for="avatar" 
                                               class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Pilih Foto
                                        </label>
                                        <input type="file" 
                                               name="avatar" 
                                               id="avatar" 
                                               accept="image/jpeg,image/png,image/jpg"
                                               class="hidden"
                                               onchange="previewAvatar(event)">
                                        
                                        {{-- ============================================ --}}
                                        {{-- Tombol Hapus → submit form terpisah        --}}
                                        {{-- ============================================ --}}
                                        @if($user->avatar)
                                        <button type="button" 
                                                onclick="hapusAvatar()"
                                                class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 border border-red-300 rounded-lg transition duration-150">
                                            Hapus Foto
                                        </button>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Format: JPG, PNG. Maksimal 2MB. Rasio: <strong>1:1</strong> (persegi), minimal <strong>128×128px</strong>.
                                    </p>
                                    @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Personal Information --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h3>

                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('user.profile.index') }}" 
                               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition duration-150 text-center">
                                Batal
                            </a>
                        </div>

                    </form>{{-- akhir form utama --}}

                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview avatar sebelum upload
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            // Ganti div/img dengan img baru
            preview.outerHTML = `<img src="${e.target.result}" 
                                      id="avatar-preview"
                                      alt="Preview" 
                                      class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">`;
        }
        reader.readAsDataURL(file);
    }
}

// Submit form hapus avatar yang terpisah
function hapusAvatar() {
    if (confirm('Hapus foto profil?')) {
        document.getElementById('form-delete-avatar').submit();
    }
}
</script>
@endpush

</x-layouts.app>