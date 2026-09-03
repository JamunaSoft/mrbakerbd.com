<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    function register(Request $request)
    {
        $customer_email = Customer::where('email', $request->email)->first();
        $customer_phone = Customer::where('phone', $request->phone)->first();

        if(is_null($customer_email) && is_null($customer_phone))
        {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->password = Hash::make($request->password);
            $customer->save();

            $customer->status = "Registration successful.";
        } else {
            return response(['status' => 'User account is already exists!'], 200);
        }

        return response($customer, 200);
    }

    function login(Request $request)
    {
        $customer_email = Customer::where('email', $request->email)->first();
        $customer_phone = Customer::where('phone', $request->phone)->first();

        if (!$customer_email || !Hash::check($request->password, $customer_email->password))
        {
            if (!$customer_phone || !Hash::check($request->password, $customer_phone->password))
            {
                return response(['message' => ['These credentials do not match our records.']], 404);
            } else {
                $customer = $customer_phone;
            }
        } else {
            $customer = $customer_email;
        }

        return response($customer, 200);
    }

    function slide()
    {
        $slide = Slide::where('status', 1)->where('position', 1)->first();

        if (!$slide)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/slides/';

        if(!is_null($slide->image)){ $slide->image = $path . $slide->image; }

        return response($slide, 200);
    }

	function categories(Request $request)
    {
        $categories = Category::where('parent_id', $request->parent_id)->where('status', 1)->orderBy('position', 'asc')->get();

        if (!$categories)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/categories/';

        for($i=0; count($categories) > $i; $i++)
        {
            if(!is_null($categories[$i]['icon'])){ $categories[$i]['icon'] = $path . $categories[$i]['icon']; }
            if(!is_null($categories[$i]['image'])){ $categories[$i]['image'] = $path . $categories[$i]['image']; }
        }

        return response($categories, 200);
    }

    function category(Request $request)
    {
        $category = Category::where('id', $request->id)->where('status', 1)->first();

        if (!$category)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/categories/';

        if(!is_null($category->icon)){ $category->icon = $path . $category->icon; }
        if(!is_null($category->image)){ $category->image = $path . $category->image; }

        return response($category, 200);
    }

    function featured()
    {
        $products = Product::where('status', 1)->where('featured', 1)->inRandomOrder()->get();

        for($i=0; count($products) > $i; $i++)
        {
            if($products[$i]->type == 2)
            {
                $products[$i]->regular_price = $products[$i]->details->min('regular_price')." ৳ - ".$products[$i]->details->max('regular_price')." ৳";
            } else {
                $products[$i]->regular_price = $products[$i]->regular_price." ৳";
            }
        }

        if (!$products)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/products/thumb/';

        for($i=0; count($products) > $i; $i++)
        {
            if(!is_null($products[$i]['image'])){ $products[$i]['image'] = $path . $products[$i]['image']; }
        }

        return response($products, 200);
    }

    function products(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->where('status', 1)->orderBy('id', 'desc')->get();

        for($i=0;count($products) > $i;$i++)
        {
            if($products[$i]->type == 2)
            {
                $products[$i]->regular_price = $products[$i]->details->min('regular_price')." ৳ - ".$products[$i]->details->max('regular_price')." ৳";
            }else{
                $products[$i]->regular_price = $products[$i]->regular_price." ৳";
            }
        }

        if (!$products)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/products/thumb/';

        for($i=0; count($products) > $i; $i++)
        {
            if(!is_null($products[$i]['image'])){ $products[$i]['image'] = $path . $products[$i]['image']; }
        }

        return response($products, 200);
    }

    function product(Request $request)
    {
        $product = Product::where('id', $request->id)->where('status', 1)->first();

        if (!$product)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $path = Cache::get('settings')->admin_url . 'images/products/';

        if(!is_null($product->image)){ $product->image = $path . $product->image; }

        return response($product, 200);
    }

    function productdetails(Request $request)
    {
        $productdetails = ProductDetail::where('product_id', $request->product_id)->orderBy('id', 'asc')->get();

        if (!$productdetails)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($productdetails, 200);
    }

    function productdetail(Request $request)
    {
        $productdetail = ProductDetail::where('id', $request->id)->first();

        if (!$productdetail)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($productdetail, 200);
    }

    function pages(Request $request)
    {
        $pages = Page::where('status', 1)->orderBy('position', 'asc')->get();

        if (!$pages)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($pages, 200);
    }

    function page(Request $request)
    {
        $page = Page::where('id', $request->id)->where('status', 1)->first();

        if (!$page)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($page, 200);
    }

    function settings()
    {
        $settings = Setting::where('id', 1)->first();

        if (!$settings)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($settings, 200);
    }

    function orders(Request $request)
    {
        $orders = Order::where('customer_id', $request->customer_id)->where('status', '!=', 'Canceled')->get();

        if (!$orders)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($orders, 200);
    }

    function order(Request $request)
    {
        $order = Order::where('id', $request->id)->first();

        if (!$order)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($order, 200);
    }

    function orderdetails(Request $request)
    {
        $orderdetails = OrderDetail::where('order_id', $request->orderid)->get();

        if (!$orderdetails)
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        for($i=0; count($orderdetails) > $i; $i++)
        {
            $product = Product::where('id', $orderdetails[$i]['product_id'])->first();

            $orderdetails[$i]['product_name'] = $product->name;
        }

        return response($orderdetails, 200);
    }

    function placeorder(Request $request)
    {
        if(count($request->cart) > 0)
        {
            $cart = $request->cart;

            $total_qty = 0;
            $total_price = 0;
            foreach($cart as $c)
            {
                $total_qty += $c['qty'];
                $total_price += $c['qty'] * $c['price'];
            }

            $order = new Order;
            $order->customer_id = $request->customer_id;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->email = $request->email;
            $order->notes = $request->notes;
            $order->total_qty = $total_qty;
            $order->total_price = $total_price;
            $order->shipping_method = 1;
            $order->shipping_charge = Cache::get('settings')->delivery_charge;
            $order->payable_amount = $total_price + Cache::get('settings')->delivery_charge;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 0;
            $order->status = "Pending";
            $order->source = "App";
            $order->amount = $order->payable_amount;
            $order->currency = "BDT";
            $order->save();

            foreach($cart as $c)
            {
                $product = Product::where('id', $c['id'])->first();

                $orderdetail = new OrderDetail;
                $orderdetail->order_id = $order->id;
                $orderdetail->product_id = $c['id'];
                $orderdetail->type = $product->type;
                $orderdetail->size = $c['size'];
                $orderdetail->label = $c['label'];
                $orderdetail->qty = $c['qty'];
                $orderdetail->price = $c['price'];
                $orderdetail->save();
            }

            $response['cus_name'] = $request->name;
            $response['currency'] = "BDT";
            $response['total_amount'] = $order->payable_amount;
            $response['tran_id'] = $order->id;
        } else {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        return response($response, 200);
    }

    function updateinfo(Request $request)
    {
        $customer = Customer::where('id', $request->customer_id)->first();

        if (!$customer || !Hash::check($request->password, $customer->password))
        {
            return response(['message' => ['These credentials do not match our records.']], 404);
        }

        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->save();

        return response($customer, 200);
    }

    function updatepass(Request $request)
    {
        $customer = Customer::where('id', $request->customer_id)->first();

        if (!$customer || !Hash::check($request->password, $customer->password))
        {
            return response(['status' => ['Incorrect password!']], 401);
        }

        $customer->password = Hash::make($request->password_new);
        $customer->save();

        $customer->status = "Your password has been changed.";

        return response($customer, 200);
    }
}
