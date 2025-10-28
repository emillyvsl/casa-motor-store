<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loja pública
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('site.home');
})->name('home');

Route::get('/produtos', function () {
    return view('site.product');
})->name('products');

Route::get('/carrinho', function () {
    return view('site.cart');
})->name('cart');

/*
|--------------------------------------------------------------------------
| Rotas de conta (somente placeholders por enquanto)
|--------------------------------------------------------------------------
*/
Route::get('/perfil', function () {
    return view('site.profile');
})->middleware('auth')->name('profile');

Route::get('/meus-pedidos', function () {
    return view('site.orders');
})->middleware('auth')->name('orders');

/*
|--------------------------------------------------------------------------
| Dashboard administrativo (Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Perfil de usuário (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Autenticação padrão Breeze
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
