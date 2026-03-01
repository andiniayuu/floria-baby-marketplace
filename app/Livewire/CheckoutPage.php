<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;

#[Title('Checkout - FloriaBaby')]
class CheckoutPage extends Component
{
    public $checkout_items = [];
    public $checkout_total = 0;

    public $selected_address_id;
    public $shipping_method = 'regular';
    public $payment_method  = 'midtrans';
    public $notes;

    public $addresses;

    // =====================================================
    // Tarif ongkir per kg (dalam gram → dibulatkan ke atas)
    // =====================================================
    const SHIPPING_RATE = [
        'regular' => ['per_kg' => 8000,  'min' => 15000, 'label' => 'Reguler', 'eta' => '3-5 hari kerja'],
        'express' => ['per_kg' => 15000, 'min' => 25000, 'label' => 'Express', 'eta' => '1-2 hari kerja'],
    ];

    const DEFAULT_WEIGHT = 500; // gram, jika produk tidak punya berat

    public function mount()
    {
        $this->checkout_items = session('checkout_items', []);
        $this->checkout_total = session('checkout_total', 0);
        $checkout_timestamp   = session('checkout_timestamp');

        if (
            empty($this->checkout_items) ||
            !$checkout_timestamp ||
            (now()->timestamp - $checkout_timestamp) > 1800
        ) {
            session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);
            session()->flash('error', 'Sesi checkout kamu sudah kedaluwarsa.');
            return redirect()->route('cart');
        }

        $this->validateCheckoutItems();

        $this->addresses = UserAddress::where('user_id', auth()->id())
            ->orderBy('is_primary', 'desc')
            ->latest()
            ->get();

        $primaryAddress = $this->addresses->firstWhere('is_primary', true);
        if ($primaryAddress) {
            $this->selected_address_id = $primaryAddress->id;
        }
    }

    protected function validateCheckoutItems()
    {
        $productIds = collect($this->checkout_items)->pluck('product_id');
        $products   = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($this->checkout_items as $key => $item) {
            $product = $products->get($item['product_id']);

            if (!$product || $product->stock <= 0) {
                unset($this->checkout_items[$key]);
                continue;
            }

            if ($product->stock < $item['quantity']) {
                $this->checkout_items[$key]['quantity'] = $product->stock;
            }

            if ($product->price != $item['unit_amount']) {
                $this->checkout_items[$key]['unit_amount'] = $product->price;
            }

            // Simpan weight per item untuk kalkulasi
            $this->checkout_items[$key]['weight']       = $product->weight ?? self::DEFAULT_WEIGHT;
            $this->checkout_items[$key]['total_amount'] =
                $this->checkout_items[$key]['quantity'] * $this->checkout_items[$key]['unit_amount'];
        }

        $this->checkout_items = array_values($this->checkout_items);
        $this->checkout_total = collect($this->checkout_items)->sum('total_amount');

        if (empty($this->checkout_items)) {
            return redirect()->route('cart');
        }
    }

    // =====================================================
    // COMPUTED PROPERTIES
    // =====================================================

    public function getSubtotalProperty(): int
    {
        return (int) collect($this->checkout_items)->sum('total_amount');
    }

    /**
     * Total berat semua item dalam gram
     */
    public function getTotalWeightProperty(): int
    {
        $total = 0;
        foreach ($this->checkout_items as $item) {
            $weight = $item['weight'] ?? self::DEFAULT_WEIGHT;
            $total += $weight * $item['quantity'];
        }
        return max($total, 1);
    }

    /**
     * Berat dalam kg (dibulatkan ke atas, min 1kg)
     */
    public function getTotalWeightKgProperty(): int
    {
        return (int) ceil($this->totalWeight / 1000);
    }

    /**
     * Kalkulasi ongkir berdasarkan berat & metode
     */
    public function getShippingCostProperty(): int
    {
        $rate      = self::SHIPPING_RATE[$this->shipping_method] ?? self::SHIPPING_RATE['regular'];
        $weightKg  = $this->totalWeightKg;
        $calculated = $weightKg * $rate['per_kg'];

        return max($calculated, $rate['min']);
    }

    public function getGrandTotalProperty(): int
    {
        return $this->subtotal + $this->shippingCost;
    }

    public function getPrimaryAddressProperty()
    {
        return $this->addresses?->firstWhere('is_primary', true);
    }

    /**
     * Info lengkap shipping cost untuk ditampilkan di UI
     */
    public function getShippingInfoProperty(): array
    {
        $rate     = self::SHIPPING_RATE[$this->shipping_method] ?? self::SHIPPING_RATE['regular'];
        $weightKg = $this->totalWeightKg;

        return [
            'weight_gram' => $this->totalWeight,
            'weight_kg'   => $weightKg,
            'rate_per_kg' => $rate['per_kg'],
            'calculated'  => $weightKg * $rate['per_kg'],
            'minimum'     => $rate['min'],
            'final'       => $this->shippingCost,
            'label'       => $rate['label'],
            'eta'         => $rate['eta'],
        ];
    }

    // =====================================================
    // PLACE ORDER Utama saat order
    // =====================================================

    public function placeOrder()
    {
        Log::info('placeOrder() dipanggil', [
            'payment_method'      => $this->payment_method,
            'selected_address_id' => $this->selected_address_id,
            'total_weight'        => $this->totalWeight,
            'shipping_cost'       => $this->shippingCost,
            'grand_total'         => $this->grandTotal,
        ]);

        // 1. Validasi alamat
        if (empty($this->selected_address_id)) {
            $this->addError('selected_address_id', 'Pilih alamat pengiriman terlebih dahulu.');
            return;
        }

        $address = UserAddress::where('id', $this->selected_address_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$address) {
            $this->addError('selected_address_id', 'Alamat tidak valid.');
            return;
        }

        DB::beginTransaction();

        try {
            // 2. Ambil seller_id dari produk pertama di cart
            $firstProductId = $this->checkout_items[0]['product_id'] ?? null;
            $sellerId = $firstProductId
                ? Product::find($firstProductId)?->user_id
                : null;

            // 3. Buat order
            $order = Order::create([
                'user_id'         => auth()->id(),
                'seller_id'       => $sellerId, // ← seller_id dari produk
                'address_id'      => $address->id,
                'grand_total'     => $this->grandTotal,
                'shipping_cost'   => $this->shippingCost,
                'total_weight'    => $this->totalWeight,
                'shipping_method' => $this->shipping_method,
                'payment_method'  => $this->payment_method,
                'payment_status'  => 'pending',
                'status'          => 'pending',
                'notes'           => $this->notes,
            ]);

            // 4. Buat order items & kurangi stok
            foreach ($this->checkout_items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak cukup untuk: {$product->name}");
                }

                $order->items()->create([
                    'product_id'   => $product->id,
                    'quantity'     => $item['quantity'],
                    'unit_amount'  => $item['unit_amount'],
                    'total_amount' => $item['total_amount'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // 5. === COD ===
            if ($this->payment_method === 'cod') {
                // ✅ COD: status tetap 'pending' — bayar saat barang sampai
                // JANGAN markAsPaid() di sini
                DB::commit();

                foreach ($this->checkout_items as $item) {
                    CartManagement::removeCartItem($item['product_id']);
                }
                session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);

                return redirect()->route('user.order.success', $order->id);
            }

            // 6. === MIDTRANS ===
            Config::$serverKey    = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized  = config('services.midtrans.is_sanitized');
            Config::$is3ds        = config('services.midtrans.is_3ds');

            $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email'      => auth()->user()->email,
                    'phone'      => $address->phone ?? '',
                ],
                'item_details'     => $this->getItemDetails($order),
                'enabled_payments' => [
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_va',
                    'permata_va',
                    'other_va',
                    'gopay',
                    'shopeepay',
                    'qris',
                ],
                'callbacks' => [
                    'finish' => route('user.payment.finish', $order->id),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap token berhasil dibuat', ['order_id' => $order->id]);

            $order->update([
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $midtransOrderId,
            ]);

            DB::commit();

            foreach ($this->checkout_items as $item) {
                CartManagement::removeCartItem($item['product_id']);
            }
            session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);

            $this->dispatch('open-midtrans-popup', [
                'token'      => $snapToken,
                'successUrl' => route('user.payment.success', $order->id),
                'pendingUrl' => route('user.payment.finish', $order->id),
                'myOrderUrl' => route('user.my-orders.show', $order->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout gagal', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            $this->addError('checkout', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    private function getItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'       => $item->product_id,
                'price'    => (int) $item->unit_amount,
                'quantity' => $item->quantity,
                'name'     => substr($item->product->name ?? 'Product', 0, 50),
            ];
        }

        if ($order->shipping_cost > 0) {
            $items[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim - ' . ucfirst($order->shipping_method),
            ];
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.checkout-page', [
            'primaryAddress' => $this->primaryAddress,
            'shippingInfo'   => $this->shippingInfo,
        ]);
    }
}
