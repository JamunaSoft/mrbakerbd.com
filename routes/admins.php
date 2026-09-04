<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth', 'role:Admin|Manager']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/profile', [HomeController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingController::class, 'settings'])->name('settings');
    Route::post('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');

        // User routes
        Route::resource('users', UserController::class);

        // Page routes
        Route::resource('pages', PageController::class);

        // Category routes
        Route::resource('categories', CategoryController::class);
        Route::get('/flavours', [CategoryController::class, 'flavours'])->name('flavour.index');
        Route::get('/flavours/create', [CategoryController::class, 'createFlavour'])->name('flavour.create');
        Route::post('/flavours', [CategoryController::class, 'storeFlavour'])->name('flavour.store');
        Route::delete('/flavour/delete/{flavour}', [CategoryController::class, 'destroyFlavour'])->name('flavour.destroy');


        // Slide routes
        Route::resource('slides', SlideController::class);


        // Product routes
        Route::resource('products', ProductController::class);
        Route::get('/product/copy/{product}', [ProductController::class, 'copy'])->name('products.copy');
        Route::delete('products/gallery/{image}', [ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');
        Route::delete('products/image/{product}', [ProductController::class, 'deleteMainImage'])->name('products.image.delete');



        // Customer routes
        Route::resource('customers', CustomerController::class);


        // Order routes
        Route::resource('orders', OrderController::class);

    // Order view route (outside permission middleware)
    Route::get('/order/view/{order}', [OrderController::class, 'view'])->name('order.view');

    Route::get('/contacts', [HomeController::class, 'contacts'])->name('contact.index');
    Route::get('/feedbacks', [HomeController::class, 'feedbacks'])->name('feedback.index');

});
