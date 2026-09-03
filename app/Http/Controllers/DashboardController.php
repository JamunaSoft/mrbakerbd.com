<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->with('details.product')->get();
        return view('frontend.pages.user.dashboard', compact('user', 'orders'));
    }
}
