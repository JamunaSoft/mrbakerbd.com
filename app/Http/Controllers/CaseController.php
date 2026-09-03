<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function clearCache() {
        Artisan::call('cache:clear');
       return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');
    }

    //Reoptimized class loader:
    public function optimize() {
        Artisan::call('optimize');
        return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');

    }

    //Route cache:
    public function routeCache() {
        Artisan::call('route:cache');
        return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');

    }

    //Clear Route cache:
    public function routeClear() {
        Artisan::call('route:clear');
        return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');

    }

    //Clear View cache:
    public function viewClear() {
        Artisan::call('view:clear');
        return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');

    }

    //Clear Config cache:
    public function configCache() {
        Artisan::call('config:cache');
        return redirect()->route('home')->with('success', 'Cache Cleared Successfully! Thank You.');

    }

}
