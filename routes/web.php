<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ShippingProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerGoogleAuthController;
use App\Http\Controllers\site\ProductController as SiteProductController;
use App\Http\Controllers\site\ProfileController as SiteProfileController;

/*
|--------------------------------------------------------------------------
| Loja pública (site)
|--------------------------------------------------------------------------
*/

Route::name('site.')->group(function () {
    Route::get('/', fn() => view('site.home'))->name('home');
    Route::get('/produtos', [SiteProductController::class, 'index'])->name('products');
    Route::get('/produtos/{slug}', [SiteProductController::class, 'show'])->name('products.show');
    Route::post('/produtos/{product}/avaliar', [SiteProductController::class, 'review'])
        ->name('products.review');
    Route::get('/carrinho', fn() => view('site.cart'))->name('cart');
});

/*
|--------------------------------------------------------------------------
| Área autenticada do cliente (loja)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->name('customer.')->group(function () {
    Route::get('/perfil', [SiteProfileController::class, 'index'])->name('profile');
    Route::get('/meus-pedidos', fn() => view('site.orders'))->name('orders');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
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
        Route::resource('products', ProductController::class)->names('admin.products');
        Route::resource('categories', CategoryController::class)->names('admin.categories');
        Route::resource('shipping-profiles', ShippingProfileController::class)->names('admin.shipping-profiles');
    });


Route::get('/login/google', [CustomerGoogleAuthController::class, 'redirect'])
    ->name('customer.google.redirect');

Route::get('/customer/google/callback', [CustomerGoogleAuthController::class, 'callback'])
    ->name('customer.google.callback');


require __DIR__ . '/auth.php';
