<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Products - FloriaBaby')]
class ProductsPage extends Component
{
    use WithPagination;

    const PAGINATION_THRESHOLD = 20;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 0;

    #[Url]
    public $sort = 'latest';

    public function updatingSelectedCategories() { $this->resetPage(); }
    public function updatingSelectedBrands()     { $this->resetPage(); }
    public function updatingFeatured()           { $this->resetPage(); }
    public function updatingOnSale()             { $this->resetPage(); }
    public function updatingPriceRange()         { $this->resetPage(); }
    public function updatingSort()               { $this->resetPage(); }

    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        session()->push('cart', $product_id);

        LivewireAlert::title('Sukses!')
            ->text('Produk ditambahkan')
            ->success()
            ->confirmButtonText('OK')
            ->show();
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        }

        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        // Hitung total produk sesuai filter
        $totalCount = (clone $productQuery)->count();
        $usePagination = $totalCount > self::PAGINATION_THRESHOLD;

        // Urutkan: in-stock dulu, out-of-stock belakang
        $productQuery->orderByRaw('CASE WHEN stock > 0 THEN 0 ELSE 1 END');

        $products = $usePagination
            ? $productQuery->paginate(self::PAGINATION_THRESHOLD)
            : $productQuery->get();

        return view('livewire.products-page', [
            'products'       => $products,
            'totalCount'     => $totalCount,
            'usePagination'  => $usePagination,
            'brands'         => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories'     => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}