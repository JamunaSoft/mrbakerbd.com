<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
		$c_count = DB::table('customers')->count();
		$o_count = DB::table('orders')->count();
		$po_count = DB::table('orders')->where('status', 'Pending')->count();
		$co_count = DB::table('orders')->where('status', 'Complete')->count();

		$top_selling_pro = Product::where('sales', '>', 0)->orderBy('sales', 'desc')->limit(10)->get();
		$processing_orders = Order::where('status', 'Processing')->orderBy('id', 'desc')->get();
		$most_viewed_pro = Product::where('views', '>', 0)->orderBy('views', 'desc')->limit(10)->get();

        return view('backend.home', ['c_count' => $c_count, 'o_count' => $o_count, 'po_count' => $po_count, 'co_count' => $co_count, 'top_selling_pro' => $top_selling_pro, 'processing_orders' => $processing_orders, 'most_viewed_pro' => $most_viewed_pro]);
    }

    public function profile()
    {
        return view('backend.profile');
    }

    public function update(Request $request)
    {
        $user = User::where('id', Auth::id())->first();

        if(!is_null($request->password) && !is_null($request->password_new) && !is_null($request->password_confirmation))
        {
            if(Hash::check($request->password, $user->password))
            {
                if($request->password_new === $request->password_confirmation)
                {
                    $user->password = Hash::make($request->password_new);

                    $user->save();

                    Auth::logout();
                } else {
                    session()->flash('fail', 'Password mismatch!');
                }
            } else {
                session()->flash('fail', 'Wrong password!');
            }
        }

        return redirect()->route('admin.profile');
    }

    public function contacts()
    {
        $contacts = DB::table('contacts')->orderBy('id', 'desc')->get();
        return view('backend.contacts.index', compact('contacts'));
    }
    public function feedbacks()
    {
        $feedbacks = DB::table('feedback')->orderBy('id', 'desc')->get();
        return view('backend.feedbacks.index', compact('feedbacks'));
    }
}
