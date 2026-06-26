<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Farmer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('farmer')->name('farmer.')->middleware(['auth', 'farmer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Farmer\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/products', App\Http\Controllers\Farmer\ProductController::class);
    Route::get('/orders', [App\Http\Controllers\Farmer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Farmer\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [App\Http\Controllers\Farmer\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{id}/confirm-payment', [App\Http\Controllers\Farmer\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
});

/*
|--------------------------------------------------------------------------
| Buyer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('buyer')->name('buyer.')->middleware(['auth', 'buyer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Buyer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/marketplace', [App\Http\Controllers\Buyer\MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/{id}', [App\Http\Controllers\Buyer\MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::get('/orders', [App\Http\Controllers\Buyer\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [App\Http\Controllers\Buyer\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [App\Http\Controllers\Buyer\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/confirm-payment', [App\Http\Controllers\Buyer\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');

    // Credit system
    Route::get('/credit', [App\Http\Controllers\Buyer\CreditController::class, 'index'])->name('credit.index');
    Route::get('/credit/apply', [App\Http\Controllers\Buyer\CreditController::class, 'apply'])->name('credit.apply');
    Route::post('/credit', [App\Http\Controllers\Buyer\CreditController::class, 'store'])->name('credit.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users management
    Route::resource('/users', App\Http\Controllers\Admin\UserController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/users/{id}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.update-status');

    // Commissions management
    Route::resource('/commissions', App\Http\Controllers\Admin\CommissionController::class)->only(['index', 'store', 'update', 'destroy']);

    // Orders management
    Route::resource('/orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
    Route::patch('/orders/{id}/resolve', [App\Http\Controllers\Admin\OrderController::class, 'resolveDispute'])->name('orders.resolve');

    // Categories management
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Credit management
    Route::get('/credits', [App\Http\Controllers\Admin\CreditController::class, 'index'])->name('credits.index');
    Route::get('/credits/{id}', [App\Http\Controllers\Admin\CreditController::class, 'show'])->name('credits.show');
    Route::patch('/credits/{id}/approve', [App\Http\Controllers\Admin\CreditController::class, 'approve'])->name('credits.approve');
    Route::patch('/credits/{id}/reject', [App\Http\Controllers\Admin\CreditController::class, 'reject'])->name('credits.reject');
});

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes (Messages & Reviews)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Reviews
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});
