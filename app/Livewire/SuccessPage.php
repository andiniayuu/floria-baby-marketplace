<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Success - FloriaBaby')]
class SuccessPage extends Component
{
    #[Url]
    public $order_id;

    #[Url]
    public $transaction_status;

    public function render()
    {
        $order = Order::with('address')
            ->where('user_id', auth()->id())
            ->latest()
            ->firstOrFail();

        // Handle Midtrans callback
        if ($this->order_id && $this->transaction_status) {
            // Verifikasi status pembayaran dari Midtrans
            if (in_array($this->transaction_status, ['capture', 'settlement'])) {
                $order->update(['payment_status' => 'paid']);
            } elseif (in_array($this->transaction_status, ['pending'])) {
                $order->update(['payment_status' => 'pending']);
            } elseif (in_array($this->transaction_status, ['deny', 'expire', 'cancel'])) {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('cancel');
            }
        }

        return view('livewire.success-page', [
            'order'   => $order,
            'address' => $order->address,
        ]);
    }
}