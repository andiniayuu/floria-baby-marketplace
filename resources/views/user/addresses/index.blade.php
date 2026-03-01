<x-layouts.app>
@section('title', 'Alamat Saya')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Alamat Saya</h1>
            <a href="{{ route('user.addresses.create', ['from' => 'checkout']) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Alamat Baru
            </a>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        {{-- Address List --}}
        @forelse($addresses as $address)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4 hover:shadow-md transition duration-150">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    {{-- Label & Badge --}}
                    <div class="flex items-center gap-2 mb-3">
                        @if($address->label)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                            {{ $address->label }}
                        </span>
                        @endif
                        
                        @if($address->is_primary)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                            Alamat Utama
                        </span>
                        @endif
                    </div>

                    {{-- Recipient Info --}}
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        {{ $address->recipient_name }}
                    </h3>
                    <p class="text-gray-600 mb-2">{{ $address->phone }}</p>

                    {{-- Full Address --}}
                    <p class="text-gray-700 leading-relaxed mb-2">
                        {{ $address->formatted_address }}
                    </p>

                    {{-- Notes --}}
                    @if($address->notes)
                    <p class="text-sm text-gray-500 italic">
                        Catatan: {{ $address->notes }}
                    </p>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 ml-4">
                    @if(!$address->is_primary)
                    <form action="{{ route('user.addresses.set-primary', $address) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                title="Jadikan Alamat Utama">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('user.addresses.edit', $address) }}" 
                       class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150"
                       title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>

                    <form action="{{ route('user.addresses.destroy', $address) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Alamat</h3>
            <p class="text-gray-500 mb-6">Tambahkan alamat untuk mempermudah proses checkout</p>
            <a href="{{ route('user.addresses.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                Tambah Alamat Pertama
            </a>
        </div>
        @endforelse
        

    </div>
</div>
</x-layouts.app>