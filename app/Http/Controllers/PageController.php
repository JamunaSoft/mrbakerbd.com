<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Page;


class PageController extends Controller
{



    public function cart()
    {
        return view('frontend.pages.cart');
    }

    public function checkout()
    {
        $settings = DB::table('settings')->first();
        $dhaka_areas = DB::table('dhaka_areas')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();
        if(auth()->check()){
            $user = auth()->user();
        }else{
            $user = null;
        }

        $shipping = $settings->delivery_charge;

        return view('frontend.pages.checkout', compact('dhaka_areas','settings', 'user','shipping'));
    }


    public function outlets()
    {
        return view('frontend.pages.outlets');
    }

    public function feedback()
    {
        return view('frontend.pages.feedback');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function about()
    {
        return view('frontend.pages.about');
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('frontend.pages.page', compact('page'));
    }

}
