<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SellerRequestController;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrdersDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES (USER / CUSTOMER)
|--------------------------------------------------------------------------
*/

// Public
Route::get('/', HomePage::class)->name('home');
Route::get('/categories', CategoriesPage::class)->name('categories');
Route::get('/products', ProductsPage::class)->name('products');
Route::get('/products/{slug}', ProductsDetailPage::class)->name('products.detail');
Route::middleware('auth')->get('/cart', CartPage::class)->name('cart');

// Guest (Belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class)->name('register');
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

// Authenticated USER
Route::middleware(['auth', 'role:user,seller'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/checkout', CheckoutPage::class)->name('checkout');
        Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
        Route::get('/my-orders/{order_id}', MyOrdersDetailPage::class)->name('my-orders.show');
        Route::get('/order/success/{order_id}', SuccessPage::class)->name('order.success');
        Route::get('/order/cancel', CancelPage::class)->name('order.cancel');

        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
            Route::get('/{order}/success', [PaymentController::class, 'success'])->name('success');
            Route::get('/{order}/finish', [PaymentController::class, 'finish'])->name('finish');
            Route::get('/{order}/status', [PaymentController::class, 'checkStatus'])->name('status');
        });

        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('index');
            Route::get('/create', [AddressController::class, 'create'])->name('create');
            Route::post('/', [AddressController::class, 'store'])->name('store');
            Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
            Route::put('/{address}', [AddressController::class, 'update'])->name('update');
            Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
            Route::post('/{address}/set-primary', [AddressController::class, 'setPrimary'])->name('set-primary');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::get('/password', [ProfileController::class, 'editPassword'])->name('password');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
            Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('delete-avatar');
        });

        // Seller Routes
        Route::prefix('seller')->name('seller.')->group(function () {
            Route::get('/create', [SellerRequestController::class, 'create'])->name('create');
            Route::post('/store', [SellerRequestController::class, 'store'])->name('store');
            Route::get('/status', [SellerRequestController::class, 'status'])->name('status');
            Route::delete('/cancel', [SellerRequestController::class, 'cancel'])->name('cancel');
        });
    });

//Webhook Midtrans 
Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

// Logout
Route::middleware('auth')->post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');