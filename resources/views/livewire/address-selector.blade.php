<div class="space-y-4">
    {{-- Selected Address Display --}}
    @if($selectedAddress)
    <div class="bg-white border-2 border-blue-600 rounded-lg p-4">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    @if($selectedAddress->label)
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                        {{ $selectedAddress->label }}
                    </span>
                    @endif
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                        ✓ Dipilih
                    </span>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-1">
                    {{ $selectedAddress->recipient_name }}
                </h3>
                <p class="text-sm text-gray-600 mb-1">{{ $selectedAddress->phone }}</p>
                <p class="text-sm text-gray-700">
                    {{ $selectedAddress->formatted_address }}
                </p>
            </div>
            
            <button wire:click="openModal" 
                    type="button"
                    class="ml-4 px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition">
                Ganti
            </button>
        </div>
    </div>
    @else
    <button wire:click="openModal" 
            type="button"
            class="w-full border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-600 hover:bg-blue-50 transition text-center">
        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <p class="text-sm text-gray-600">Pilih Alamat Pengiriman</p>
    </button>
    @endif

    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" name="address_id" value="{{ $selectedAddressId }}">

    {{-- Address Selection Modal --}}
    @if($showAddressModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Pilih Alamat Pengiriman</h3>
                <button wire:click="closeModal" 
                        type="button"
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-3">
                @forelse($addresses as $address)
                <button wire:click="selectAddress({{ $address->id }})" 
                        type="button"
                        class="w-full text-left border-2 {{ $selectedAddressId == $address->id ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }} rounded-lg p-4 hover:border-blue-400 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if($address->label)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded">
                                    {{ $address->label }}
                                </span>
                                @endif
                                
                                @if($address->is_primary)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                    Utama
                                </span>
                                @endif
                            </div>

                            <h4 class="font-semibold text-gray-900 mb-1">
                                {{ $address->recipient_name }}
                            </h4>
                            <p class="text-sm text-gray-600 mb-1">{{ $address->phone }}</p>
                            <p class="text-sm text-gray-700">
                                {{ $address->formatted_address }}
                            </p>
                        </div>

                        @if($selectedAddressId == $address->id)
                        <div class="ml-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </button>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">Belum ada alamat tersimpan</p>
                    <a href="{{ route('user.addresses.create') }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Tambah Alamat
                    </a>
                </div>
                @endforelse
            </div>

            @if($addresses->count() > 0)
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 p-4">
                <a href="{{ route('user.addresses.create') }}" 
                   target="_blank"
                   class="block text-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition">
                    + Tambah Alamat Baru
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>