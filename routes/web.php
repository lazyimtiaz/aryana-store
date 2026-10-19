<?php

use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ContentPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterSubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/shop', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/product/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

Route::get('/brand/{brand:slug}', [BrandController::class, 'show'])
    ->name('brands.show');

Route::get('/brands', [BrandController::class, 'index'])
    ->name('brands.index');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/sale', [ProductController::class, 'sale'])
    ->name('products.sale');

Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])
    ->name('products.new-arrivals');

Route::get('/combos', [ProductController::class, 'combos'])
    ->name('products.combos');
Route::post('/newsletter/subscribe', [NewsletterSubscriptionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

/*
|--------------------------------------------------------------------------
| Store Content
|--------------------------------------------------------------------------
*/

Route::get('/faq', [ContentPageController::class, 'faq'])
    ->name('pages.faq');

Route::get('/contact', [ContactController::class, 'show'])
    ->name('pages.contact');

Route::get('/pages/{slug}', [ContentPageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('pages.show');


/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart', [CartController::class, 'store'])
    ->name('cart.store');

Route::patch('/cart/{cartItem}', [CartController::class, 'update'])
    ->whereNumber('cartItem')
    ->name('cart.update');

Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
    ->whereNumber('cartItem')
    ->name('cart.destroy');


/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])
        ->name('checkout.create');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])
        ->name('checkout.success');
    //coupons
    Route::post('/cart/coupon', [CouponController::class, 'apply'])
        ->name('cart.coupon.apply');

    Route::delete('/cart/coupon', [CouponController::class, 'remove'])
        ->name('cart.coupon.remove');
});


/*
|--------------------------------------------------------------------------
| Customer Account
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/account', [AccountController::class, 'dashboard'])
        ->name('account.dashboard');

    Route::get('/account/orders', [AccountController::class, 'orders'])
        ->name('account.orders');

    Route::get('/account/orders/{order}', [AccountController::class, 'order'])
        ->name('account.orders.show');

    Route::get('/account/addresses', [AddressController::class, 'index'])
        ->name('account.addresses');

    Route::get('/account/addresses/create', [AddressController::class, 'create'])
        ->name('account.addresses.create');

    Route::post('/account/addresses', [AddressController::class, 'store'])
        ->name('account.addresses.store');

    Route::get('/account/addresses/{address}/edit', [AddressController::class, 'edit'])
        ->name('account.addresses.edit');

    Route::patch('/account/addresses/{address}', [AddressController::class, 'update'])
        ->name('account.addresses.update');

    Route::delete('/account/addresses/{address}', [AddressController::class, 'destroy'])
        ->name('account.addresses.destroy');

    Route::post('/account/addresses/{address}/default', [AddressController::class, 'setDefault'])
        ->name('account.addresses.default');

    Route::get('/account/profile', [ProfileController::class, 'edit'])
        ->name('account.profile');

    Route::patch('/account/profile', [ProfileController::class, 'update'])
        ->name('account.profile.update');

    Route::delete('/account/profile', [ProfileController::class, 'destroy'])
        ->name('account.profile.destroy');
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])
        ->name('wishlist.store');

    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])
        ->name('wishlist.destroy');

    //Product reviews
    Route::post('/product/{product}/reviews', [ReviewController::class, 'store'])
        ->name('products.reviews.store');
});



/*
|--------------------------------------------------------------------------
| Breeze Authentication
|--------------------------------------------------------------------------
|
| login, register, logout, password reset, email verification, etc.
|
*/

require __DIR__.'/auth.php';
