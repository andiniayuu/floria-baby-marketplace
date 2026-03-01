<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Detail')]
class MyOrdersDetailPage extends Component
{
    public int $order_id;

    public function mount(int $order_id): void
    {
        $this->order_id = $order_id;

        $order = Order::findOrFail($order_id);

        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized');
        }
    }

    public function confirmReceived(): void
    {
        $order = Order::findOrFail($this->order_id);

        // Double-check kepemilikan
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya bisa dikonfirmasi jika status shipped
        if ($order->status !== 'shipped') {
            return;
        }

        $order->update([
            'status' => 'delivered',
        ]);

        session()->flash('success', 'Pesanan berhasil dikonfirmasi diterima. Terima kasih sudah berbelanja!');
    }

    public function render()
    {
        $order = Order::with(['items.product', 'user', 'address'])
            ->findOrFail($this->order_id);

        return view('livewire.my-orders-detail-page', [
            'order' => $order,
        ]);
    }
}