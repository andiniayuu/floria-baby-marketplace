<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success - FloriaBaby')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $order = Order::with('address')
            ->where('user_id', auth()->id())
            ->latest()
            ->firstOrFail();

        if ($this->session_id) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($this->session_id);

            if ($session->payment_status !== 'paid') {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('cancel');
            }

            $order->update(['payment_status' => 'paid']);
        }

        return view('livewire.success-page', [
            'order'   => $order,
            'address' => $order->address,
        ]);
    }
}
