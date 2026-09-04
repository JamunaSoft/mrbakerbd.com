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
        $orders = Order::query()
            ->select([
                'id', 'name', 'phone', 'address', 'created_at', 'total_qty',
                'payable_amount', 'payment_status', 'status', 'source',
            ])
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

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
        $data = $request->validate([
            'status' => ['required', 'string', 'in:Pending,Processing,Complete,Cancelled,Failed'],
            'send_email' => ['nullable', 'boolean'],
        ]);

        if($order->status != 'Complete' && $data['status'] == 'Complete')
        {
            foreach($order->details as $od)
            {
                $product = Product::where('id', $od->product_id)->first();
                $product->sales = $product->sales + 1;
                $product->save();
            }
        }

        $order->status = $data['status'];
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
