<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - FloriaBaby')]
class ProductsDetailPage extends Component
{
    public $slug;
    public $product;
    public $quantity = 1;

    public function updatedQuantity($value)
    {
        $value = (int) $value;

        if ($value < 1) {
            $this->quantity = 1;
            return;
        }

        if ($value > $this->product->stock) {
            $this->quantity = $this->product->stock;
            return;
        }

        $this->quantity = $value;
    }

    public function mount($slug)
    {
        $this->slug = $slug;
        
        $this->product = Product::where('slug', $slug)->firstOrFail();
    }

    public function increaseQty()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    // add product to cart method
    public function addToCart($product_id)
    {

        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        session()->push('cart', $product_id);

        // Tampilan Notif
        LivewireAlert::title('Sukses!')
            ->text('Produk ditambahkan')
            ->success()
            ->confirmButtonText('OK')
            ->show();
    }
    public function buyNow()
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();

        // Cek stok
        if ($product->stock < $this->quantity) {
            LivewireAlert::title('Stok Tidak Cukup')
                ->text('Jumlah melebihi stok tersedia')
                ->warning()
                ->confirmButtonText('OK')
                ->show();

            return;
        }

        // Tambah ke cart
        $total_count = CartManagement::addItemToCartWithQty(
            $product->id,
            $this->quantity
        );

        // Update cart di navbar
        $this->dispatch('update-cart-count', total_count: $total_count)
            ->to(Navbar::class);

        // Redirect ke cart
        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.products-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrfail(),
        ]);
    }
}
