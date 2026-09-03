<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<style type="text/css">
.invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<img style="max-width: 80px;padding-top: 15px;" src="{{ asset('images/'. Cache::get('settings')->logo) }}" alt="Logo">
                <h3 class="pull-right">Order #<?php echo $order->id; ?></h3>
    		</div>
    		<hr>
    		<div class="row">
				<div class="col-xs-6">
    				<address>
						<strong>Delivery Address:</strong><br>
    					{{ $order->name }}<br>
    					{{ $order->phone }}<br>
    					{{ $order->address }}<br>
    					{{ $order->email }}
    					@if(!is_null($order->delv_dt))
						<br>
						<strong>Delivery Date & Time: </strong>
						{{ DateTime::createFromFormat('Y-m-d', explode(',', $order->delv_dt)[0])->format(Cache::get('settings')->date_format) }}, {{ explode(',', $order->delv_dt)[1] }}
						@endif
    				</address>
    			</div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Placed On:</strong><br>
                        {{ DateTime::createFromFormat('Y-m-d H:i:s', $order->created_at)->format(Cache::get('settings')->date_format .', h:i A') }}
                    </address>
                </div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Method:</strong><br>
    					@if($order->payment_method == 1)
                            Cash on Delivery
                        @endif
                        @if($order->payment_method == 2)
                            Online Payment
                        @endif
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
                    <address>
                        <strong>Order Status:</strong><br>
                        {{ $order->status }}
                    </address>
                </div>
    		</div>
			<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Status:</strong><br>
    					@if($order->payment_status == 0)
                            Unpaid
                        @endif
                        @if($order->payment_status == 1)
                            Paid
                        @endif
    				</address>
    			</div>
				<div class="col-xs-6 text-right">
                    <address>
                        @if(!is_null($order->customer_id))
                        <strong>Customer:</strong><br>
                        {{ $order->customer->name.' - '.$order->customer->phone }}<br>
                        {{ $order->customer->email }}
                        @endif
                    </address>
                </div>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order Summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Product</strong></td>
        							<td><strong>Image</strong></td>
                                    <td><strong>Price</strong></td>
									<td><strong>Qty</strong></td>
        							<td class="text-right"><strong>Total</strong></td>
                                </tr>
    						</thead>
    						<tbody>
								@foreach($order->details as $od)
    							<tr>
    								<td>
                                        <a href="{{ route('product', $od->product->slug) }}" target="_blank">
                                        {{ $od->product->name }} - {{ $od->product->code }}
                                        @if(!is_null($od->size))
                                         - {{ $od->size }}
                                        @endif
                                        @if(!is_null($od->label))
                                        <br>
                                        Label: {{ $od->label }}
                                        @endif
                                        @if(!is_null($od->flavour))
                                        <br>
                                        Flavor: {{ $od->flavour }}
                                        @endif
                                        @if(!is_null($od->custom_note))
                                        <br>
                                        Custom Note: {{ $od->custom_note }}
                                        @endif
                                        </a>
                                    </td>
                                    <td><img src="{{ $od->product->image->thumbnail_url ?? '' }}" alt="Product Image" style="max-width: 50px; max-height: 50px;"></td>
									<td>{{ number_format($od->price, 2) }}</td>
    								<td>{{ $od->qty }}</td>
    								<td class="text-right">{{ number_format(($od->price * $od->qty), 2) }}</td>
    							</tr>
								@endforeach
    							<tr>
    								<td class="thick-line"><b>Notes:</b> {{ $order->notes }}</td>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-left"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right">{{ number_format($order->total_price,2) }}</td>
    							</tr>
                                @if(!is_null($order->discount))
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-left"><strong>Discount</strong></td>
    								<td class="no-line text-right">{{ number_format($order->discount,2) }}</td>
    							</tr>
                                @endif
								<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-left"><strong>Delivery Charge</strong></td>
    								<td class="no-line text-right">{{ number_format($order->shipping_charge,2) }}</td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-left"><strong>Grand Total</strong></td>
    								<td class="no-line text-right"><strong>{{ number_format($order->payable_amount,2) }}</strong></td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
