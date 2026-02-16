<?php

namespace App\Livewire;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddressSelector extends Component
{
    public $addresses;
    public $selectedAddressId;
    public $selectedAddress;
    public $showAddressModal = false;

    public function mount($preselectedId = null)
    {
        $this->loadAddresses();
        
        if ($preselectedId) {
            $this->selectAddress($preselectedId);
        } else {
            // Auto select primary address
            $primaryAddress = $this->addresses->where('is_primary', true)->first();
            if ($primaryAddress) {
                $this->selectAddress($primaryAddress->id);
            }
        }
    }

    public function loadAddresses()
    {
        $this->addresses = Auth::user()->addresses()->get();
    }

    public function selectAddress($addressId)
    {
        $this->selectedAddressId = $addressId;
        $this->selectedAddress = $this->addresses->find($addressId);
        $this->showAddressModal = false;
        
        // Emit event untuk komponen lain
        $this->dispatch('addressSelected', addressId: $addressId);
    }

    public function openModal()
    {
        $this->showAddressModal = true;
    }

    public function closeModal()
    {
        $this->showAddressModal = false;
    }

    public function render()
    {
        return view('livewire.address-selector');
    }
}