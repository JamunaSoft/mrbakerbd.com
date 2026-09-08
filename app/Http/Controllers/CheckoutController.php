<?php

namespace App\Http\Controllers;

use App\Library\Lib\SslCommerzNotification;
use App\Mail\OrderMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use stdClass;
use Illuminate\Support\Arr;
use App\Services\IpCountryService;
use App\Services\MetaConversionsApi;

class CheckoutController extends Controller
{

    public function submit(Request $request)
    {
      //  dd($request->all());
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'delivery_country' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'area' => 'required|string',
            'address_details' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'time' => 'required|string',
            'delv_d' => 'required|date',
            'password' => 'nullable|string|min:6',
            'payment_method' => 'required|in:1,2',
            'conditions' => 'required|accepted',
            'create_account' => 'nullable|in:1',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $cartKey => $item) {
            $productId = (int) explode('-', (string) $cartKey)[0];
            $product = \App\Models\Product::find($productId);
            if (!$product || !$product->status) {
                return back()->with('error', 'One of the products is no longer available.');
            }

            $expectedPrice = $product->special_price ?? $product->regular_price;
            if (!empty($item['variant_id'])) {
                $variant = $product->details()->find($item['variant_id']);
                $expectedPrice = $variant ? ($variant->special_price ?? $variant->regular_price) : null;
            }
            if ($expectedPrice === null || (float) $expectedPrice !== (float) $item['price']) {
                return back()->with('error', 'A product price has changed. Please review your cart.');
            }
            if ($product->stock !== null && (int) $product->stock < (int) $item['quantity']) {
                return back()->with('error', 'One of the products does not have enough stock.');
            }
        }

        // Handle customer
        $customer = new stdClass();
        if ($request->create_account && !empty($data['email']) && !empty($data['phone'])) {
            $customer = User::where('email', $data['email'])
                ->orWhere('phone', $data['phone'])
                ->first();
            if (!$customer) {
                $customer = User::create([
                    'name' => $data['customer_name'],
                    'email' => $data['email'],
                    'password' => Hash::make(isset($data['password']) ? $data['password'] : $data['phone']),
                    'phone' => $data['phone'],
                    'address' => $data['address_details'] . ', ' . $data['area'],
                ]);
            } else {
                if ($customer->email === $data['email']) {
                    return back()->withErrors(['email' => 'Email already exists. Please login or use a different email.'])->withInput();
                } else {
                    return back()->withErrors(['phone' => 'Phone already exists. Please login or use a different phone number.'])->withInput();
                }
            }
        } else {
            $customer->id = Auth::check() ? Auth::id() : null;
            $customer->email = $data['email'];
        }

        $settings = DB::table('settings')->first();
        // Calculate totals
        $subtotal = $this->calculateCartTotal($cart);
        $area_charge = DB::table('dhaka_areas')->where('name', $data['area'])->value('charge') ?? 0;
        session()->put('area_charge', $area_charge);
        $shipping = $data['payment_method'] == 2 ? ($settings->delivery_charge + $area_charge) : 0;
       // dd($shipping);
        $total = $subtotal + $shipping;
        $totalQty = collect($cart)->sum('quantity');
        $paymentCountry = app(IpCountryService::class)->country($request->ip());

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'name' => $data['customer_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address_details'] . ', ' . $data['area'],
            'payment_country' => $paymentCountry,
            'delivery_country' => $data['delivery_country'],
            'division' => $data['division'],
            'district' => $data['district'],
            'area' => $data['area'],
            'delv_dt' => $data['delv_d'] . ', ' . $data['time'],
            'notes' => isset($data['notes']) ? $data['notes'] : null,
            'total_qty' => $totalQty,
            'total_price' => $subtotal,
            'shipping_method' => 1,
            'shipping_charge' => $shipping,
            'payable_amount' => $total,
            'payment_method' => $data['payment_method'],
            'payment_status' => '0',
            'status' => 'Pending',
            'source' => 'web',
            'amount' => $total,
            'currency' => 'BDT',
        ]);

        // Save order details
        foreach ($cart as $id => $item) {
            $productId = explode('-', $id)[0];
            $type = isset($item['variant_id']) ? 2 : 1;
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'type' => $type,
                'size' => isset($item['size']) ? $item['size'] : null,
                'label' => isset($item['level']) ? $item['level'] : null,
                'flavour' => !empty($item['flavour']) ? (is_array($item['flavour']) ? implode(',', $item['flavour']) : (string) $item['flavour']) : null,
                'custom_note' => isset($item['custom_note']) ? $item['custom_note'] : null,
                'qty' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        if ($data['payment_method'] == 2) {
            return $this->initiateSslCommerzPayment($order);
        }

        // For COD, send notifications and clear cart
        session()->forget('cart');
        $this->sendNotifications($order);
        app(MetaConversionsApi::class)->sendPurchase($order, $request);
        session()->flash('msg', 'Thank you for your order. Your order has been placed successfully.');
      //  return redirect()->route('orderplaced');
        return redirect()->to(URL::temporarySignedRoute('order.success', now()->addHours(2), ['order_id' => $order->id]));
    }

    private function initiateSslCommerzPayment($order)
    {
       /* if (!class_exists('App\Library\SslCommerz\SslCommerzNotification')) {
            \Log::error('SslCommerzNotification class not found in App\Library\SslCommerz.');
            return redirect()->route('orderplaced')->with('error', 'Payment gateway not available.');
        }*/

        $config = config('sslcommerz');
        $post_data = [
            'store_id' => $config['apiCredentials']['store_id'],
            'store_passwd' => $config['apiCredentials']['store_password'],
            'total_amount' => $order->payable_amount,
            'currency' => 'BDT',
            'tran_id' => 'ORDER_' . $order->id,
            'success_url' => url($config['success_url']),
            'fail_url' => url($config['failed_url']),
            'cancel_url' => url($config['cancel_url']),
            'ipn_url' => url($config['ipn_url']),
            'cus_name' => $order->name,
            'cus_email' => $order->email ?: 'customer@customer.com',
            'cus_add1' => $order->address,
            'cus_add2' => '',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '0000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->phone ?: '8801XXXXXXXXX',
            'ship_name' => '',
            'ship_add1' => '',
            'ship_add2' => '',
            'ship_city' => '',
            'shipping_method' => 'NO',
            'product_name' => 'Cake-Pastry',
            'product_category' => 'Bakery',
            'product_profile' => 'food',
        ];

        try {
            $sslc = new SslCommerzNotification();
            $response = $sslc->makePayment($post_data, 'hosted');

            Log::info('SSLCommerz Payment Initiation', [
                'tran_id' => $post_data['tran_id'],
                'post_data' => $post_data,
                'response' => $response
            ]);

            if (is_array($response) && !empty($response['GatewayPageURL'])) {
                return redirect($response['GatewayPageURL']);
            }

            Log::error('SSLCommerz Payment Failed', [
                'response' => $response,
                'post_data' => $post_data
            ]);

            return redirect()->route('orderplaced')->with('error', 'Failed to initiate payment: ' . (isset($response['failedreason']) ? $response['failedreason'] : 'No valid gateway URL'));
        } catch (\Exception $e) {
            Log::error('SSLCommerz Exception', [
                'error' => $e->getMessage(),
                'post_data' => $post_data
            ]);
            return redirect()->route('orderplaced')->with('error', 'Payment initiation error: ' . $e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        Log::info('SSLCommerz Success Callback', [
            'tran_id' => $request->input('tran_id'),
            'status' => $request->input('status'),
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'all_params' => $request->all()
        ]);

        if (!class_exists('App\Library\Lib\SslCommerzNotification')) {
            Log::error('SslCommerzNotification class not found in App\Library\SslCommerz.');
            return redirect()->route('orderplaced')->with('error', 'Payment gateway not available.');
        }

        $tran_id = $request->input('tran_id');
        $order_id = isset(explode('_', $tran_id)[1]) ? explode('_', $tran_id)[1] : null;
        $order = Order::find($order_id);

        if (!$order) {
            Log::error('SSLCommerz Invalid Order', ['tran_id' => $tran_id]);
            session()->flash('error', 'Invalid transaction.');
            return redirect()->route('orderplaced');
        }

        if ($order->status === 'Processing' || $order->status === 'Complete') {
            Log::info('SSLCommerz Order Already Processed', ['order_id' => $order->id]);
            session()->flash('status', 'Transaction is already successful.');
            session()->forget('cart');
            return redirect()->route('orderplaced');
        }

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $order->payable_amount, 'BDT');

        if ($validation && in_array($request->input('status'), ['VALID', 'VALIDATED'])) {
            $order->update([
                'payment_status' => 1,
                'status' => 'Processing'
            ]);
            $this->sendNotifications($order);
            app(MetaConversionsApi::class)->sendPurchase($order, $request);
            session()->forget('cart');
            session()->flash('status', 'Thank you for your payment. Your order has been successfully placed.');

        } else {
            Log::error('SSLCommerz Success Validation Failed', [
                'tran_id' => $tran_id,
                'order_id' => $order->id,
                'validation' => $validation,
                'request_status' => $request->input('status'),
                'amount' => $request->input('amount'),
                'expected_amount' => $order->payable_amount
            ]);
            $order->update(['status' => 'Failed']);
            session()->flash('error', 'Payment validation failed. Please try again or contact support.');
        }

        session()->flash('source', $order->source);
        return redirect()->to(URL::temporarySignedRoute('order.success', now()->addHours(2), ['order_id' => $order->id]));
    }

    public function paymentFail(Request $request)
    {
        Log::info('SSLCommerz Fail Callback', [
            'tran_id' => $request->input('tran_id'),
            'status' => $request->input('status'),
            'all_params' => $request->all()
        ]);

        $tran_id = $request->input('tran_id');
        $order_id = isset(explode('_', $tran_id)[1]) ? explode('_', $tran_id)[1] : null;
        $order = Order::find($order_id);

        if (!$order) {
            Log::error('SSLCommerz Invalid Order', ['tran_id' => $tran_id]);
            session()->flash('error', 'Invalid transaction.');
            return redirect()->route('orderplaced');
        }

        if ($order->status === 'Processing' || $order->status === 'Complete') {
            Log::info('SSLCommerz Order Already Processed', ['order_id' => $order->id]);
            session()->flash('status', 'Transaction is already successful.');
        } else {
            $order->update(['status' => 'Failed']);
            session()->flash('error', 'Transaction failed.');
        }

        session()->flash('source', $order->source);
        return redirect()->route('orderplaced');
    }

    public function paymentCancel(Request $request)
    {
        Log::info('SSLCommerz Cancel Callback', [
            'tran_id' => $request->input('tran_id'),
            'status' => $request->input('status'),
            'all_params' => $request->all()
        ]);

        $tran_id = $request->input('tran_id');
        $order_id = isset(explode('_', $tran_id)[1]) ? explode('_', $tran_id)[1] : null;
        $order = Order::find($order_id);

        if (!$order) {
            Log::error('SSLCommerz Invalid Order', ['tran_id' => $tran_id]);
            session()->flash('error', 'Invalid transaction.');
            return redirect()->route('orderplaced');
        }

        if ($order->status === 'Processing' || $order->status === 'Complete') {
            Log::info('SSLCommerz Order Already Processed', ['order_id' => $order->id]);
            session()->flash('status', 'Transaction is already successful.');
        } else {
            $order->update(['status' => 'Cancelled']);
            session()->flash('error', 'Transaction cancelled.');
        }

        session()->flash('source', $order->source);
        return redirect()->route('orderplaced');
    }

    public function ipn(Request $request)
    {
        Log::info('SSLCommerz IPN Callback', [
            'tran_id' => $request->input('tran_id'),
            'status' => $request->input('status'),
            'all_params' => $request->all()
        ]);

        if (!class_exists('App\Library\Lib\SslCommerzNotification')) {
            Log::error('SslCommerzNotification class not found in App\Library\SslCommerz.');
            return response()->json(['status' => 'error', 'message' => 'Payment gateway not available'], 500);
        }

        $tran_id = $request->input('tran_id');
        $order_id = isset(explode('_', $tran_id)[1]) ? explode('_', $tran_id)[1] : null;
        $order = Order::find($order_id);

        if (!$order) {
            Log::error('SSLCommerz Invalid Order', ['tran_id' => $tran_id]);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        if ($order->status === 'Processing' || $order->status === 'Complete') {
            Log::info('SSLCommerz Order Already Processed', ['order_id' => $order->id]);
            return response()->json(['status' => 'success']);
        }

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $order->payable_amount, 'BDT');

        if ($validation && in_array($request->input('status'), ['VALID', 'VALIDATED'])) {
            $order->update([
                'payment_status' => 1,
                'status' => 'Processing'
            ]);
            $this->sendNotifications($order);
            app(MetaConversionsApi::class)->sendPurchase($order);
            session()->forget('cart');
        } elseif ($request->input('status') === 'FAILED') {
            $order->update(['status' => 'Failed']);
        } elseif ($request->input('status') === 'CANCELLED') {
            $order->update(['status' => 'Cancelled']);
        }

        return response()->json(['status' => 'success']);
    }

    public function getAreaCharge(Request $request)
    {
        $area = $request->input('area');
        $charge = DB::table('dhaka_areas')->where('name', $area)->value('charge') ?? 0;
        return response()->json(['charge' => $charge]);
    }

    private function sendNotifications(Order $order)
    {
        $settings = DB::table('settings')->first();
        try {
            if ($order->email) {
                session()->flash('subject', 'Order #' . $order->id . ' is Placed - ' . $settings->site_title);
                Mail::to($order->email)->send(new OrderMail($order));
            } elseif ($order->customer_id) {
                $customer = User::find($order->customer_id);
                if ($customer && $customer->email) {
                    session()->flash('subject', 'Order #' . $order->id . ' is Placed - ' . $settings->site_title);
                    Mail::to($customer->email)->send(new OrderMail($order));
                }
            }

            session()->flash('subject', 'New Order #' . $order->id . ' - ' . $settings->site_title);
            Mail::to($settings->email)->send(new OrderMail($order));
        } catch (\Exception $e) {
            Log::error('Email notification failed: ' . $e->getMessage());
        }

        try {
            $phone = null;
            if ($order->phone) {
                $phone = preg_replace('/\D/', '', $order->phone);
                $phone = '8801' . substr($phone, -9);
            } elseif ($order->customer_id) {
                $customer = User::find($order->customer_id);
                if ($customer && $customer->phone) {
                    $phone = preg_replace('/\D/', '', $customer->phone);
                    $phone = '8801' . substr($phone, -9);
                }
            }

            if ($phone) {
                $data = [
                    'api_key' => 'R60012055f01b04fc43442.05394758',
                    'type' => 'text',
                    'contacts' => $phone,
                    'senderid' => '8809612446650',
                    'msg' => 'Payment successful. Your order #' . $order->id . ' is ' . $order->status . '. Mr. Baker Cake & Pastry Shop Ltd.'
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://880sms.com/smsapi');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                curl_close($ch);
            }

            $adminPhone = preg_replace('/\D/', '', $settings->phone);
            $adminPhone = '8801' . substr($adminPhone, -9);
            $adminData = [
                'api_key' => 'R60012055f01b04fc43442.05394758',
                'type' => 'text',
                'contacts' => $adminPhone,
                'senderid' => '8809612446650',
                'msg' => 'Order #' . $order->id . ' is Placed. Mr. Baker Cake & Pastry Shop Ltd.'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://880sms.com/smsapi');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $adminData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            Log::error('SMS notification failed: ' . $e->getMessage());
        }
    }

    private function calculateCartTotal($cart)
    {
        return collect($cart)->reduce(function ($carry, $item) {
            return $carry + ((float) $item['price'] * (int) $item['quantity']);
        }, 0);
    }

    public function success($order_id)
    {
        $order = Order::with(['details.product:id,name,code'])
            ->select('id', 'phone', 'email','payable_amount', 'total_qty', 'total_price', 'discount','shipping_charge','payment_status', 'payment_method', 'status')
            ->findOrFail($order_id);

        $purchaseEvent = \App\Services\MarketingEvents::purchase($order);
        $enhancedConversionData = $purchaseEvent ? \App\Services\MarketingEvents::enhancedConversions($order) : [];

        return view('frontend.pages.success', compact('order', 'purchaseEvent', 'enhancedConversionData'));
    }

/*    public function submit(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'address_details' => 'required|string',
            'area' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'time' => 'required|string',
            'delv_d' => 'required|date',
            'password' => 'nullable|string|min:6',
            'payment_method' => 'required|in:1,2',
            'conditions' => 'required|accepted',
        ]);

        // Optional account creation
        if ($request->has('create_account') && $request->filled('password')) {
            $user = User::create([
                'name' => $data['customer_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            Auth::login($user);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $this->calculateCartTotal($cart);
        $shipping = $data['payment_method'] == 1 ? 100 : 0;
        $total = $subtotal + $shipping;
        $totalQty = collect($cart)->sum('quantity');

        // Create order
        $order = Order::create([
            'customer_id' => Auth::id(),
            'name' => $data['customer_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address_details'] . ', ' . $data['area'],
            'delv_dt' => $data['delv_d'] . ' ' . $data['time'],
            'total_qty' => $totalQty,
            'total_price' => $subtotal,
            'shipping_charge' => $shipping,
            'payable_amount' => $total,
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'status' => 'pending',
            'source' => 'web',
            'amount' => $total,
            'currency' => 'BDT',
        ]);

        // Save order details
        foreach ($cart as $id => $item) {
            $productId = explode('-', $id)[0];
            $type = isset($item['variant_id']) ? 2 : 1;
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'type' => $type,
                'size' => $item['size'] ?? null,
                'label' => $item['level'] ?? null,
                'qty' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        if ($data['payment_method'] == 2) {
            // Initiate SSLCommerz payment
            return $this->initiateSslCommerzPayment($order, $cart);
        }

        // For COD, clear cart and redirect
        session()->forget('cart');
        return redirect()->route('home')->with('success', 'Your order has been placed.');
    }

    private function initiateSslCommerzPayment(Order $order, array $cart)
    {
        $post_data = [];
        $post_data['store_id'] = env('SSLCOMMERZ_STORE_ID', 'testbox');
        $post_data['store_passwd'] = env('SSLCOMMERZ_STORE_PASSWORD', 'qwerty');
        $post_data['total_amount'] = $order->payable_amount;
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = 'ORDER_' . $order->id . '_' . uniqid();
        $post_data['success_url'] = route('payment.success');
        $post_data['fail_url'] = route('payment.fail');
        $post_data['cancel_url'] = route('payment.cancel');
        $post_data['ipn_url'] = route('ipn');
        $post_data['cus_name'] = $order->name;
        $post_data['cus_email'] = $order->email;
        $post_data['cus_add1'] = $order->address;
        $post_data['cus_add2'] = '';
        $post_data['cus_phone'] = $order->phone;
        $post_data['shipping_method'] = 'NO';
        $post_data['product_name'] = 'Cart Items';
        $post_data['product_category'] = 'General';
        $post_data['product_profile'] = 'general';

        // Initialize SSLCommerz
        $sslc = new SslCommerzNotification();
        $response = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($response)) {
            return redirect()->route('home')->with('error', 'Failed to initiate payment.');
        }

        // Redirect to SSLCommerz payment page
        return redirect($response['GatewayPageURL']);
    }

    public function paymentSuccess(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[1] ?? null;
        $order = Order::find($order_id);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $order->payable_amount, 'BDT');

        if ($validation) {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing',
            ]);
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Payment successful. Your order has been placed.');
        }

        $order->update(['status' => 'failed']);
        return redirect()->route('home')->with('error', 'Payment validation failed.');
    }

    public function paymentFail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[1] ?? null;
        $order = Order::find($order_id);

        if ($order) {
            $order->update(['status' => 'failed']);
        }

        return redirect()->route('home')->with('error', 'Payment failed. Please try again.');
    }

    public function paymentCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[1] ?? null;
        $order = Order::find($order_id);

        if ($order) {
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->route('home')->with('error', 'Payment cancelled.');
    }

    public function ipn(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = explode('_', $tran_id)[1] ?? null;
        $order = Order::find($order_id);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $order->payable_amount, 'BDT');

        if ($validation && $request->input('status') === 'VALID') {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing',
            ]);
            session()->forget('cart');
        } elseif ($request->input('status') === 'FAILED') {
            $order->update(['status' => 'failed']);
        } elseif ($request->input('status') === 'CANCELLED') {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json(['status' => 'success']);
    }

    private function calculateCartTotal($cart)
    {
        return collect($cart)->reduce(function ($carry, $item) {
            return $carry + ((float) $item['price'] * (int) $item['quantity']);
        }, 0);
    }*/

}
