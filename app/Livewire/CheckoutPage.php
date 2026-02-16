<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Checkout - FloriaBaby')]
class CheckoutPage extends Component
{
    // Checkout items dari session
    public $checkout_items = [];
    public $checkout_total = 0;

    // Form fields
    public $selected_address_id;
    public $shipping_method = 'regular';
    public $payment_method = 'midtrans'; // ✅ SET DEFAULT VALUE
    public $notes;

    // Addresses
    public $addresses;

    // Shipping costs
    public $shipping_costs = [
        'regular' => 15000,
        'express' => 25000,
    ];

    /**
     * Mount
     */
    public function mount()
    {
        // 1️⃣ Cek checkout items
        $this->checkout_items = session('checkout_items', []);
        $this->checkout_total = session('checkout_total', 0);
        $checkout_timestamp = session('checkout_timestamp');

        // 2️⃣ Validasi session
        if (
            empty($this->checkout_items) ||
            !$checkout_timestamp ||
            (now()->timestamp - $checkout_timestamp) > 1800
        ) {
            session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);
            session()->flash('error', 'Your checkout session has expired. Please try again.');
            return redirect()->route('cart');
        }

        // 3️⃣ Validasi stock
        $this->validateCheckoutItems();

        // 4️⃣ Load addresses
        $this->addresses = UserAddress::where('user_id', auth()->id())
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 5️⃣ Auto-select primary address
        $primaryAddress = $this->addresses->firstWhere('is_primary', true);
        if ($primaryAddress) {
            $this->selected_address_id = $primaryAddress->id;
        }
    }

    /**
     * Validasi stock real-time
     */
    protected function validateCheckoutItems()
    {
        $productIds = collect($this->checkout_items)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $hasChanges = false;
        $removedItems = [];

        foreach ($this->checkout_items as $key => $item) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                unset($this->checkout_items[$key]);
                $removedItems[] = $item['name'];
                $hasChanges = true;
                continue;
            }

            if ($product->stock < $item['quantity']) {
                if ($product->stock > 0) {
                    $this->checkout_items[$key]['quantity'] = $product->stock;
                    $this->checkout_items[$key]['total_amount'] = $product->stock * $item['unit_amount'];
                    $hasChanges = true;
                    session()->flash('warning', "{$item['name']} quantity adjusted to {$product->stock}");
                } else {
                    unset($this->checkout_items[$key]);
                    $removedItems[] = $item['name'];
                    $hasChanges = true;
                }
            }

            if ($product->price != $item['unit_amount']) {
                $this->checkout_items[$key]['unit_amount'] = $product->price;
                $this->checkout_items[$key]['total_amount'] = $item['quantity'] * $product->price;
                $hasChanges = true;
            }
        }

        $this->checkout_items = array_values($this->checkout_items);

        if ($hasChanges) {
            $this->checkout_total = collect($this->checkout_items)->sum('total_amount');
            session([
                'checkout_items' => $this->checkout_items,
                'checkout_total' => $this->checkout_total
            ]);

            if (!empty($removedItems)) {
                session()->flash('error', 'Some items were removed: ' . implode(', ', $removedItems));
            }
        }

        if (empty($this->checkout_items)) {
            session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);
            session()->flash('error', 'No items available for checkout.');
            return redirect()->route('cart');
        }
    }

    /**
     * Process Order
     */
    public function placeOrder()
    {
        // 1️⃣ Validasi
        $this->validate([
            'selected_address_id' => 'required|exists:user_addresses,id',
            'shipping_method' => 'required|in:regular,express',
            'payment_method' => 'required|in:midtrans,cod,transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        // ✅ LOG 1: Cek payment method
        \Log::info('Payment Method Selected:', ['method' => $this->payment_method]);

        // 2️⃣ Load address
        $address = UserAddress::where('id', $this->selected_address_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$address) {
            session()->flash('error', 'Invalid address selected.');
            return;
        }

        // 3️⃣ DB Transaction
        DB::beginTransaction();

        try {
            // ... (kode validasi stock) ...

            // Calculate totals
            $subtotal = collect($this->checkout_items)->sum('total_amount');
            $shipping_amount = $this->shipping_costs[$this->shipping_method];
            $grand_total = $subtotal + $shipping_amount;

            // Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'grand_total' => $grand_total,
                'subtotal' => $subtotal,
                'total_amount' => $grand_total,
                'payment_method' => $this->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'currency' => 'IDR',
                'shipping_method' => $this->shipping_method,
                'shipping_amount' => $shipping_amount,
                'shipping_cost' => $shipping_amount,
                'notes' => $this->notes,
                'shipping_address' => $address->formatted_address,
            ]);

            // ✅ LOG 2: Cek order created
            \Log::info('Order Created:', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method
            ]);

            // Create Order Items & Decrease Stock
            foreach ($this->checkout_items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $item['unit_amount'],
                    'total_amount' => $item['total_amount'],
                ]);

                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            // Remove dari cart
            foreach ($this->checkout_items as $item) {
                CartManagement::removeCartItem($item['product_id']);
            }

            // Clear session
            session()->forget(['checkout_items', 'checkout_total', 'checkout_timestamp']);

            DB::commit();

            // ✅ LOG 3: Cek redirect condition
            \Log::info('Before Redirect:', [
                'payment_method' => $this->payment_method,
                'is_midtrans' => $this->payment_method === 'midtrans',
                'order_id' => $order->id
            ]);

            // REDIRECT BERDASARKAN PAYMENT METHOD
            if ($this->payment_method === 'midtrans') {
                \Log::info('Redirecting to Midtrans payment page');
                return redirect()->route('user.payment', ['order' => $order->id]);
            }

            // Untuk COD/Transfer
            \Log::info('Redirecting to success page');
            session()->flash('success', 'Order placed successfully!');
            return redirect()->route('user.order.success', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Creation Failed:', ['error' => $e->getMessage()]);
            session()->flash('error', 'Order failed: ' . $e->getMessage());
            $this->validateCheckoutItems();
            return null;
        }
    }

    /**
     * Computed properties
     */
    public function getSubtotalProperty()
    {
        return collect($this->checkout_items)->sum('total_amount');
    }

    public function getShippingCostProperty()
    {
        return $this->shipping_costs[$this->shipping_method] ?? 0;
    }

    public function getGrandTotalProperty()
    {
        return $this->subtotal + $this->shipping_cost;
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
