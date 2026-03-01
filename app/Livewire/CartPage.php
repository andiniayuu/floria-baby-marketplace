<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Title('Cart - FloriaBaby')]
class CartPage extends Component
{
    public $cart_items = [];
    public array $selectedItems = [];
    public bool $selectAll = false;

    /**
     * Mount: Inisialisasi cart saat component di-load
     */
    public function mount()
    {
        $this->loadCartItems();
        $this->autoSelectAll();
    }

    /**
     * ⚡ OPTIMIZED: Load cart items dengan eager loading
     * Menghindari N+1 query problem
     */
    protected function loadCartItems()
    {
        $cartData = CartManagement::getCartItemsFromCookie();

        if (empty($cartData)) {
            $this->cart_items = [];
            return;
        }

        // 🔥 OPTIMASI: Ambil semua product dalam 1 query
        $productIds = collect($cartData)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // 🔄 Sinkronisasi data cart dengan database
        $this->cart_items = collect($cartData)
            ->map(function ($item) use ($products) {
                $product = $products->get($item['product_id']);

                // Skip jika produk sudah tidak ada
                if (!$product) {
                    return null;
                }

                // Update data real-time dari database
                $item['stock'] = $product->stock;
                $item['name'] = $product->name;
                $item['unit_amount'] = $product->price;
                $item['image'] = $product->images[0] ?? 'default.jpg';

                // 🛡️ VALIDASI: Quantity tidak boleh melebihi stock
                if ($item['quantity'] > $item['stock']) {
                    $item['quantity'] = max(1, $item['stock']);
                }

                // Recalculate total
                $item['total_amount'] = $item['quantity'] * $item['unit_amount'];

                return $item;
            })
            ->filter() // Remove null items
            ->values()
            ->toArray();

        // 🔄 Update cookie dengan data terbaru
        CartManagement::updateCartItemsInCookie($this->cart_items);
    }

    /**
     * Auto select semua item saat pertama load (UX improvement)
     */
    protected function autoSelectAll()
    {
        if (count($this->cart_items) > 0) {
            $this->selectedItems = collect($this->cart_items)
                ->pluck('product_id')
                ->toArray();
            $this->selectAll = true;
        }
    }

    /**
     * Update selectAll checkbox ketika individual checkbox berubah
     */
    public function updatedSelectedItems()
    {
        $this->selectAll = count($this->selectedItems) === count($this->cart_items)
            && count($this->cart_items) > 0;
    }

    /**
     * Update ketika selectAll checkbox di-toggle langsung
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = collect($this->cart_items)
                ->pluck('product_id')
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    /**
     * 🗑️ Remove single item dari cart
     */
    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id);

        // Remove dari selectedItems
        $this->selectedItems = array_values(
            array_filter($this->selectedItems, fn($id) => $id != $product_id)
        );

        // Update navbar count
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))
            ->to(Navbar::class);

        $this->dispatch('notify', type: 'success', message: 'Item removed from cart');
        $this->updatedSelectedItems();
    }

    /**
     * 🗑️ Remove multiple selected items
     */
    public function removeSelectedItems()
    {
        if (count($this->selectedItems) === 0) {
            $this->dispatch('notify', type: 'warning', message: 'No items selected');
            return;
        }

        $count = count($this->selectedItems);

        foreach ($this->selectedItems as $product_id) {
            CartManagement::removeCartItem($product_id);
        }

        $this->loadCartItems();
        $this->selectedItems = [];
        $this->selectAll = false;

        $this->dispatch('update-cart-count', total_count: count($this->cart_items))
            ->to(Navbar::class);

        $this->dispatch('notify', type: 'success', message: "{$count} items removed");
    }

    /**
     * ➕ Increase quantity dengan validasi stock REAL-TIME
     */
    public function increaseQty($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            $this->dispatch('notify', type: 'error', message: 'Product not found');
            return;
        }

        $cartItem = collect($this->cart_items)
            ->firstWhere('product_id', $product_id);

        if (!$cartItem) {
            return;
        }

        // 🛡️ VALIDASI STOCK REAL-TIME
        if ($cartItem['quantity'] >= $product->stock) {
            $this->dispatch(
                'notify',
                type: 'warning',
                message: 'Stock limit reached (' . $product->stock . ' available)'
            );
            return;
        }

        // Update quantity
        CartManagement::incrementQuantityToCartItem($product_id);

        // ⚡ OPTIMASI: Update langsung tanpa reload semua
        foreach ($this->cart_items as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity']++;
                $item['total_amount'] = $item['quantity'] * $item['unit_amount'];
                break;
            }
        }
    }

    /**
     * ➖ Decrease quantity - auto remove jika qty = 1
     */
    public function decreaseQty($product_id)
    {
        $cartItem = collect($this->cart_items)
            ->firstWhere('product_id', $product_id);

        if (!$cartItem) {
            return;
        }

        // Auto remove jika quantity sudah 1
        if ($cartItem['quantity'] <= 1) {
            $this->removeItem($product_id);
            return;
        }

        CartManagement::decrementQuantityToCartItem($product_id);

        // ⚡ OPTIMASI: Update langsung tanpa reload semua
        foreach ($this->cart_items as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity']--;
                $item['total_amount'] = $item['quantity'] * $item['unit_amount'];
                break;
            }
        }
    }

    /**
     * 🛒 Checkout selected items dengan validasi KETAT
     */
    public function checkoutSelected()
    {
        // 1️⃣ Validasi ada item yang dipilih
        if (count($this->selectedItems) === 0) {
            $this->dispatch('notify', type: 'warning', message: 'Please select at least one item');
            return;
        }

        // 2️⃣ Ambil selected items
        $selectedCartItems = collect($this->cart_items)
            ->whereIn('product_id', $this->selectedItems)
            ->values()
            ->toArray();

        // 3️⃣ 🔥 VALIDASI STOCK REAL-TIME dengan LOCKING
        DB::beginTransaction();

        try {
            $productIds = collect($selectedCartItems)->pluck('product_id');

            // Lock rows untuk mencegah race condition
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $outOfStock = [];

            foreach ($selectedCartItems as $item) {
                $product = $products->get($item['product_id']);

                if (!$product) {
                    $outOfStock[] = $item['name'] . ' (product not found)';
                    continue;
                }

                if ($product->stock < $item['quantity']) {
                    $outOfStock[] = $item['name'] . ' (only ' . $product->stock . ' available)';
                }
            }

            DB::commit();

            // 4️⃣ Handle out of stock
            if (count($outOfStock) > 0) {
                $items = implode(', ', $outOfStock);
                $this->dispatch('notify', type: 'error', message: 'Out of stock: ' . $items);
                $this->loadCartItems(); // Reload untuk update stock
                return;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', type: 'error', message: 'Failed to validate stock');
            return;
        }

        // 5️⃣ Simpan ke session untuk checkout page
        session([
            'checkout_items' => $selectedCartItems,
            'checkout_total' => collect($selectedCartItems)->sum('total_amount'),
            'checkout_timestamp' => now()->timestamp
        ]);

        foreach ($this->selectedItems as $product_id) {
            CartManagement::removeCartItem($product_id);
        }
        
        // Reload cart items di component
        $this->loadCartItems();
        $this->selectedItems = [];
        $this->selectAll = false;

        // Update navbar count
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))
            ->to(Navbar::class);

        // 6️⃣ Redirect ke halaman checkout
        return redirect()->route('user.checkout');
    }

    /**
     * 💰 Total semua item di cart
     */
    #[Computed]
    public function cartTotal()
    {
        return collect($this->cart_items)->sum('total_amount');
    }

    /**
     * 💰 Total item yang dipilih untuk checkout
     */
    #[Computed]
    public function checkoutTotal()
    {
        return collect($this->cart_items)
            ->whereIn('product_id', $this->selectedItems)
            ->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
