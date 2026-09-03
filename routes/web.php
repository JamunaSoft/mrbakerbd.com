<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;


Auth::routes(['verify' => true]);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'product'])->name('product');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/search', [ProductController::class, 'search'])->name('search');


Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::post('/order', [CheckoutController::class, 'submit'])->name('checkout.submit');
Route::post('/success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/fail', [CheckoutController::class, 'paymentFail'])->name('payment.fail');
Route::post('/cancel', [CheckoutController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('/mrbipn', [CheckoutController::class, 'ipn'])->name('ipn');
Route::post('/get-area-charge', [CheckoutController::class, 'getAreaCharge'])->name('get.area.charge');
Route::get('/order-placed', function () { return view('frontend.pages.success'); })->name('orderplaced');
Route::get('/order-success/{order_id}', [CheckoutController::class, 'success'])->name('order.success');


Route::post('/test-csrf', function (Request $request) {
    return response()->json(['status' => 'success', 'data' => $request->all()]);
})->withoutMiddleware('csrf');

Route::get('/outlets', [PageController::class, 'outlets'])->name('outlets');
Route::get('/feedback', [PageController::class, 'feedback'])->name('feedback');
Route::post('/feedback/submit', [HomeController::class, 'feedbackSubmit'])->name('feedback.submit');
Route::get('/about-mr-baker', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');


Route::get('/clear-cache', [CaseController::class, 'clearCache'])->name('clear-cache');
Route::get('/optimize', [CaseController::class, 'optimize'])->name('optimize');
Route::get('/route-cache', [CaseController::class, 'routeCache'])->name('route-cache');
Route::get('/route-clear', [CaseController::class, 'routeClear'])->name('route-clear');
Route::get('/view-clear', [CaseController::class, 'viewClear'])->name('view-clear');
Route::get('/config-cache', [CaseController::class, 'configCache'])->name('config-cache');


Route::get('/mail-test', function () {
    try {
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('kamrul@nextbell.com') // change this
            ->subject('Laravel Mail Test');
        });

        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error mail: ' . $e->getMessage();
    }
});


Route::get('/{slug}', [PageController::class, 'page'])->name('page');

Route::group(['middleware' => ['auth', 'role:User']], function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});





