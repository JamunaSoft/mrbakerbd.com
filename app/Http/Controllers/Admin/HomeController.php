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
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $counts = Cache::remember('admin.dashboard.counts', 30, function () {
            return DB::table('orders')
                ->selectRaw('COUNT(*) as orders_count')
                ->selectRaw("SUM(status = 'Pending') as pending_count")
                ->selectRaw("SUM(status = 'Complete') as complete_count")
                ->first();
        });

        $c_count = Cache::remember('admin.dashboard.customers_count', 30, fn () => DB::table('customers')->count());
        $o_count = (int) $counts->orders_count;
        $po_count = (int) $counts->pending_count;
        $co_count = (int) $counts->complete_count;

        $top_selling_pro = Product::query()
            ->select(['id', 'name', 'sales'])
            ->where('sales', '>', 0)
            ->orderByDesc('sales')
            ->limit(10)
            ->get();
        $processing_orders = Order::query()
            ->select(['id', 'name', 'phone', 'created_at', 'delv_dt', 'address', 'notes'])
            ->where('status', 'Processing')
            ->with(['details:id,order_id,product_id,qty'])
            ->latest('id')
            ->limit(10)
            ->get();
        $mostViewedPeriod = $request->query('most_viewed_period', 'week');
        if (! in_array($mostViewedPeriod, ['week', 'month', 'year', 'all'], true)) {
            $mostViewedPeriod = 'week';
        }
        $most_viewed_pro = app(\App\Services\ProductViewService::class)->mostViewed($mostViewedPeriod);

        $paymentCountryStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(payment_country, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $sourceStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(source, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();
        $deliveryCountryStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(delivery_country, ''), 'Bangladesh') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();
        $divisionStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(division, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $districtStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(district, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $areaStats = Order::query()
            ->selectRaw("COALESCE(NULLIF(area, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('backend.home', compact(
            'c_count', 'o_count', 'po_count', 'co_count', 'top_selling_pro',
            'processing_orders', 'most_viewed_pro', 'paymentCountryStats',
            'sourceStats', 'deliveryCountryStats', 'divisionStats',
            'districtStats', 'areaStats', 'mostViewedPeriod'
        ));
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
        $contacts = DB::table('contacts')->orderByDesc('id')->paginate(25)->withQueryString();
        return view('backend.contacts.index', compact('contacts'));
    }
    public function feedbacks()
    {
        $feedbacks = DB::table('feedback')->orderByDesc('id')->paginate(25)->withQueryString();
        return view('backend.feedbacks.index', compact('feedbacks'));
    }
}
