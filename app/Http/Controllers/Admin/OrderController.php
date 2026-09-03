<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index()
    {
        //$orders = Order::where('status', 'Pending')->orWhere('status', 'Processing')->orderBy('id', 'desc')->get();
        $orders = Order::orderBy('id', 'desc')->get();
      //  dd($orders);
        // return $orders;
        return view('backend.order.index', ['orders' => $orders]);
    }

    public function edit(Order $order)
    {
        if(!is_null($order))
        {
            return view('backend.order.edit', ['order' => $order]);
        } else {
        	return redirect()->route('admin.orders');
        }
    }

    public function update(Request $request, Order $order)
    {
        if($order->status != 'Complete' && $request->status == 'Complete')
        {
            foreach($order->details as $od)
            {
                $product = Product::where('id', $od->product_id)->first();
                $product->sales = $product->sales + 1;
                $product->save();
            }
        }

        $order->status = $request->status;
        $order->save();

        if($request->send_email == 1)
        {
            @get_headers(Cache::get('settings')->site_url . 'sendordermail?tran_id='. $order->id);
        }

        session()->flash('success', 'Order is updated.');
        return redirect()->route('admin.orders');
    }

    public function show(Order $order)
    {
        if(!is_null($order))
        {
            return view('backend.order.view', ['order' => $order]);
        } else {
            return redirect()->route('admin.orders');
        }
    }

    public function destroy(Order $order)
    {
        if(!is_null($order))
        {
            foreach($order->details as $od)
            {
                $od->delete();
            }

            $order->delete();
        }

        session()->flash('success', 'Order is deleted!');
        return redirect()->route('admin.orders');
    }
}
