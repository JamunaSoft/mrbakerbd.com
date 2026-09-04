<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;


Auth::routes(['verify' => true]);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /cart\nDisallow: /checkout\nDisallow: /search\nSitemap: " . url('/sitemap.xml') . "\n", 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/sitemap.xml', function () {
    $urls = collect([
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0'],
        ['loc' => route('shop'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9'],
    ])
        ->merge(Category::where('status', 1)->get()->map(fn ($category) => [
            'loc' => route('category', $category->slug), 'lastmod' => $category->updated_at?->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.8',
        ]))
        ->merge(Product::where('status', 1)->get()->map(fn ($product) => [
            'loc' => route('product', $product->slug), 'lastmod' => $product->updated_at?->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.7',
        ]))
        ->merge(Page::where('status', 1)->get()->map(fn ($page) => [
            'loc' => route('page', $page->slug), 'lastmod' => $page->updated_at?->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5',
        ]));

    return response()->view('frontend.sitemap', compact('urls'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
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
Route::get('/order-success/{order_id}', [CheckoutController::class, 'success'])
    ->middleware('signed')
    ->name('order.success');

Route::get('/outlets', [PageController::class, 'outlets'])->name('outlets');
Route::get('/feedback', [PageController::class, 'feedback'])->name('feedback');
Route::post('/feedback/submit', [HomeController::class, 'feedbackSubmit'])->name('feedback.submit');
Route::get('/about-mr-baker', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');


Route::get('/{slug}', [PageController::class, 'page'])->name('page');

Route::group(['middleware' => ['auth', 'role:User']], function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});



