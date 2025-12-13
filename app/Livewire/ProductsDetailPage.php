<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - BabyShop')]
class ProductsDetailPage extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.products-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrfail(),
        ]);
    }
}
