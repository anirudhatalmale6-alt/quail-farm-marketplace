<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pricing', [SubscriptionController::class, 'plans'])->name('pricing');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/live', [SearchController::class, 'search'])->name('search.live');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

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

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'mySubscription'])->name('subscription.status');
    Route::post('/subscription/process', [SubscriptionController::class, 'processSubscription'])->name('subscription.process');
    Route::get('/subscription/{planId}', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
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

    // Investments
    Route::get('/investments', [App\Http\Controllers\Farmer\InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/create', [App\Http\Controllers\Farmer\InvestmentController::class, 'create'])->name('investments.create');
    Route::post('/investments', [App\Http\Controllers\Farmer\InvestmentController::class, 'store'])->name('investments.store');
    Route::get('/investments/{id}', [App\Http\Controllers\Farmer\InvestmentController::class, 'show'])->name('investments.show');
    Route::get('/investments/{id}/edit', [App\Http\Controllers\Farmer\InvestmentController::class, 'edit'])->name('investments.edit');
    Route::put('/investments/{id}', [App\Http\Controllers\Farmer\InvestmentController::class, 'update'])->name('investments.update');
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

    // Investments management
    Route::get('/investments', [App\Http\Controllers\Admin\InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/{id}', [App\Http\Controllers\Admin\InvestmentController::class, 'show'])->name('investments.show');
    Route::patch('/investments/{id}/review', [App\Http\Controllers\Admin\InvestmentController::class, 'review'])->name('investments.review');
});

/*
|--------------------------------------------------------------------------
| Investor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('investor')->name('investor.')->middleware(['auth', 'investor'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Investor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/farmers', [App\Http\Controllers\Investor\FarmerBrowseController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/{id}', [App\Http\Controllers\Investor\FarmerBrowseController::class, 'show'])->name('farmers.show');
    Route::post('/invest/{applicationId}', [App\Http\Controllers\Investor\FarmerBrowseController::class, 'invest'])->name('invest');
});

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes (Messages & Reviews)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/unread-count', [App\Http\Controllers\MessageController::class, 'unreadCount'])->name('messages.unreadCount');
    Route::get('/messages/{userId}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{userId}/fetch', [App\Http\Controllers\MessageController::class, 'getMessages'])->name('messages.fetch');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Reviews
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
    Route::post('/feed/{id}/like', [FeedController::class, 'like'])->name('feed.like');
    Route::post('/feed/{id}/comment', [FeedController::class, 'comment'])->name('feed.comment');
    Route::delete('/feed/{id}', [FeedController::class, 'destroy'])->name('feed.destroy');

    // Streams
    Route::get('/streams', [StreamController::class, 'index'])->name('streams.index');
    Route::get('/streams/create', [StreamController::class, 'create'])->name('streams.create');
    Route::post('/streams', [StreamController::class, 'store'])->name('streams.store');
    Route::get('/streams/{id}', [StreamController::class, 'show'])->name('streams.show');
    Route::post('/streams/{id}/like', [StreamController::class, 'like'])->name('streams.like');
    Route::delete('/streams/{id}', [StreamController::class, 'destroy'])->name('streams.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});
