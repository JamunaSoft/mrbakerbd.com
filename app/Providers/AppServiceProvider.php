<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\ImageUploadServiceInterface;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImageUploadServiceInterface::class, ImageUploadService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $settings = Cache::rememberForever('settings_data', function () {
                return Setting::first();
            });

            [$firstLine, $secondLine] = split_address_lines($settings->address);

            $siteLogo = '/images/logo/mr-baker-logo-with-g.png';

            $mobileLogo = '/images/logo/mrbaker-mobile-logo.png' ;

            $phone = $settings->phone;

            $email = $settings->email;

            $view->with([
                'settings' => $settings,
                'addressLine1' => $firstLine,
                'addressLine2' => $secondLine,
                'siteLogo' => $siteLogo,
                'mobileLogo' => $mobileLogo,
                'phone' => $phone,
                'email' => $email,
            ]);
        });

        View::composer('*', function ($view){
            $categories = Category::orderBy('position', 'asc')->get();
            $view->with([
                'categories' => $categories,
            ]);
        });

        View::composer('*', function ($view){
            $pages = Page::all();
            $view->with([
                'pages' => $pages,
            ]);
        });
    }
}
