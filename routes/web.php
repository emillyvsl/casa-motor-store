<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeBannerController;
use App\Http\Controllers\admin\OrderController as AdminOrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\ShippingOriginController;
use App\Http\Controllers\admin\ShippingProfileController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerGoogleAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\site\CartController;
use App\Http\Controllers\site\CheckoutController;
use App\Http\Controllers\site\OrderController as SiteOrderController;
use App\Http\Controllers\site\PageController;
use App\Http\Controllers\site\ProductController as SiteProductController;
use App\Http\Controllers\site\AddressController;
use App\Http\Controllers\site\ProfileController as SiteProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loja pública (site)
|--------------------------------------------------------------------------
*/

Route::name('site.')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/servicos', [PageController::class, 'services'])->name('services');
    Route::get('/sobre', [PageController::class, 'about'])->name('about');
    Route::get('/contato', [PageController::class, 'contact'])->name('contact');
    Route::get('/produtos', [SiteProductController::class, 'index'])->name('products');
    Route::get('/produtos/{slug}', [SiteProductController::class, 'show'])->name('products.show');
    Route::post('/produtos/{product}/avaliar', [SiteProductController::class, 'review'])
        ->name('products.review');

    // Carrinho
    Route::get('/carrinho', [CartController::class, 'show'])->name('cart');
    Route::post('/carrinho/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/carrinho/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/carrinho/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/carrinho/cupom', [CartController::class, 'applyCoupon'])->name('cart.coupon');
    Route::delete('/carrinho/cupom', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::post('/carrinho/frete', [CartController::class, 'saveShipping'])->name('cart.shipping');
});

Route::post('/api/shipping/quote', [ShippingController::class, 'quote'])
    ->name('api.shipping.quote');

/*
|--------------------------------------------------------------------------
| Área autenticada do cliente (loja)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->name('customer.')->group(function () {
    Route::get('/perfil', [SiteProfileController::class, 'index'])->name('profile');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    // Addresses
    Route::post('/endereco', [AddressController::class, 'store'])->name('address.store');
    Route::delete('/endereco/{address}', [AddressController::class, 'delete'])->name('address.delete');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/sucesso/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Meus Pedidos
    Route::get('/meus-pedidos', [SiteOrderController::class, 'index'])->name('orders');
    Route::get('/meus-pedidos/{order}', [SiteOrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Autenticação do cliente (pública)
|--------------------------------------------------------------------------
*/
Route::prefix('/')->name('customer.')->group(function () {
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Dashboard administrativo (Breeze)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('/dashboard', 'dashboard')->name('dashboard');

        // Perfil admin (Breeze)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::resource('products', ProductController::class)
            ->except('show')
            ->names('admin.products');
        Route::resource('categories', CategoryController::class)
            ->except('show')
            ->names('admin.categories');
        Route::resource('home-banners', HomeBannerController::class)
            ->except('show')
            ->names('admin.home-banners')
            ->parameters(['home-banners' => 'banner']);
        Route::resource('shipping-profiles', ShippingProfileController::class)
            ->except('show')
            ->names('admin.shipping-profiles');
        Route::resource('shipping-origins', ShippingOriginController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.shipping-origins');

        // Pedidos
        Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::patch('orders/{order}/tracking', [AdminOrderController::class, 'addTracking'])->name('admin.orders.tracking');

    });

Route::get('/login/google', [CustomerGoogleAuthController::class, 'redirect'])
    ->name('customer.google.redirect');

Route::get('/customer/google/callback', [CustomerGoogleAuthController::class, 'callback'])
    ->name('customer.google.callback');

require __DIR__.'/auth.php';
