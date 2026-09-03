@extends('frontend.index')
@section('page-styles')
    @include('frontend.style.checkout')
    <style>
        #reason-dropdown-list {
            max-height: 220px;
            overflow-y: auto;
        }
    </style>
@stop
@section('content')
    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="frontend/images/breadcrumbs-bg.jpg">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">Checkout</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li><a href="{{route('shop')}}">Shop</a></li>
                    <li class="active">Checkout</li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Section checkout form-->
    <section class="section section-sm section-first bg-default text-md-start">
        <div class="container">
            <form class="rd-form form-checkout" method="POST" action="{{ route('checkout.submit') }}" id="checkout-form" novalidate>
                <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                <div class="row row-50 justify-content-center">
                    <div class="col-md-10 col-lg-6">
                        <h3 class="fw-medium">Delivery Address</h3>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-wrap">
                                    <input class="form-input" id="checkout-first-name-1" type="text" name="customer_name" placeholder="Name" required
                                           value="{{ old('customer_name', $user->name ?? '') }}" />
                                    <span class="error-message text-danger" id="error-customer_name"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex align-items-end">
                                <div class="form-wrap w-100" style="position: relative;">
                                    <div class="custom-select-mimic" style="position: relative;">
                                        <input type="hidden" name="area" id="reason-hidden" value="{{ old('area') }}">
                                        <button type="button" id="reason-dropdown-btn" class="form-input" style="width: 100%; text-align: left; background: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; cursor: pointer;">
                                            <span id="reason-selected">{{ old('area') ? old('area') : 'Please Select Area' }}</span>
                                            <span style="float: right;">&#9662;</span>
                                        </button>
                                        <ul id="reason-dropdown-list" style="display: none; position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 0 0 4px 4px; margin: 0; padding: 0; list-style: none;">
                                            @foreach($dhaka_areas as $area)
                                                <li class="dropdown-item" data-value="{{ $area->name }}" style="padding: 10px; cursor: pointer;">{{ $area->name }}</li>
                                            @endforeach
                                        </ul>
                                        <div id="reason-error" class="text-danger mt-1" style="display:none;">Please select an area.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">

                            </div>
                            <div class="col-12">
                                <div class="form-wrap">
                                    <input class="form-input" id="address" type="text" name="address_details" required
                                           value="{{ old('address_details', $user->address ?? '') }}" placeholder="Address" />
                                    <span class="error-message text-danger" id="error-address_details"></span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-wrap">
                                    <input class="form-input" id="checkout-email-1" type="email" name="email" placeholder="Email" required
                                           value="{{ old('email', $user->email ?? '') }}" />
                                    <span class="error-message text-danger" id="error-email"></span>
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-wrap">
                                    <input class="form-input" id="checkout-phone-1" type="text" name="phone" placeholder="phone" required
                                           value="{{ old('phone', $user->phone ?? '') }}" />
                                    <span class="error-message text-danger" id="error-phone"></span>
                                    @if ($errors->has('phone'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @php
                                $now = time();
                                $cutoffTime = strtotime(date('Y-m-d') . ' 16:00:00');
                                $today = date('Y-m-d');
                                $defaultDate = $now > $cutoffTime ? date('Y-m-d', strtotime('+1 day')) : $today;

                                // Calculate current time + 3 hours
                                $currentHour = date('H');
                                $threeHoursLater = $currentHour + 3;

                                $allTimeSlots = [
                                    '08:00 AM - 09:00 AM',
                                    '09:00 AM - 10:00 AM',
                                    '10:00 AM - 11:00 AM',
                                    '11:00 AM - 12:00 PM',
                                    '12:00 PM - 01:00 PM',
                                    '01:00 PM - 02:00 PM',
                                    '02:00 PM - 03:00 PM',
                                    '03:00 PM - 04:00 PM',
                                    '04:00 PM - 05:00 PM',
                                    '05:00 PM - 06:00 PM',
                                    '06:00 PM - 07:00 PM',
                                    '07:00 PM - 08:00 PM',
                                ];

                                // Function to get available time slots based on selected date
                                function getAvailableTimeSlots($selectedDate, $allSlots, $currentHour) {
                                    $today = date('Y-m-d');

                                    if ($selectedDate === $today) {
                                        // For today, only show slots that are 3+ hours from now
                                        $threeHoursLater = $currentHour + 3;
                                        $availableSlots = [];

                                        foreach ($allSlots as $slot) {
                                            // Extract hour from time slot (e.g., "08:00 AM - 09:00 AM" -> 8)
                                            $timeStart = explode(' - ', $slot)[0];
                                            $timeParts = explode(':', $timeStart);
                                            $hour = (int)$timeParts[0];
                                            $amPm = substr($timeStart, -2);

                                            // Convert to 24-hour format
                                            if ($amPm === 'PM' && $hour !== 12) {
                                                $hour += 12;
                                            } elseif ($amPm === 'AM' && $hour === 12) {
                                                $hour = 0;
                                            }

                                            // Only include if it's 3+ hours from now
                                            if ($hour >= $threeHoursLater) {
                                                $availableSlots[] = $slot;
                                            }
                                        }

                                        return $availableSlots;
                                    } else {
                                        // For future dates, show all time slots
                                        return $allSlots;
                                    }
                                }
                            @endphp
                            <div class="col-sm-6">
                                <div class="form-wrap">
                                    <input type="date" class="form-input" name="delv_d" id="delv_d" value="{{ $defaultDate }}" min="{{ $today }}" required>
                                    <span class="error-message text-danger" id="error-delv_d"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-wrap" style="position: relative;">
                                    <input type="hidden" name="time" id="delv_t" value="{{ old('time') }}">
                                    <button type="button" id="delv_t-dropdown-btn" class="form-input" style="width: 100%; text-align: left; background: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; cursor: pointer;">
                                        <span id="delv_t-selected">{{ old('time') ? old('time') : 'Select Delivery Time' }}</span>
                                        <span style="float: right;">&#9662;</span>
                                    </button>
                                    <ul id="delv_t-dropdown-list" style="display: none; position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 0 0 4px 4px; margin: 0; padding: 0; list-style: none; max-height: 220px; overflow-y: auto;">
                                        @foreach($allTimeSlots as $slot)
                                            <li class="dropdown-item" data-value="{{ $slot }}" style="padding: 10px; cursor: pointer;">{{ $slot }}</li>
                                        @endforeach
                                    </ul>
                                    <span class="error-message text-danger" id="error-time"></span>
                                </div>
                            </div>

                            <input type="hidden" id="server-date" value="{{ $today }}">
                            <input type="hidden" id="server-time" value="{{ date('H') }}">

                        </div>
                        <!-- Checkbox to toggle password field -->
                        @if(auth()->user())



                        @else
                            <label class="checkbox-inline text-transform-capitalize">
                                <input id="create_account" type="checkbox" name="create_account" value="1"/>
                                Create an account for faster checkout in future
                            </label>
                        @endif

                        <!-- Password field (hidden by default) -->
                        <div class="col-sm-12 pt-3" id="password-field" style="display: none;">
                            <div class="form-wrap">
                                <input type="password" class="form-input" name="password" id="password" placeholder="Password">
                                <span class="error-message text-danger" id="error-password"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 col-lg-6">


                        <h3 class="fw-medium mt-5">Payment Methods</h3>

                        @if($settings->payment_cod == 1)
                            <div class="box-radio">
                                <label class="radio-inline">
                                    <input name="payment_method" value="1" class="active" type="radio">Cash on Delivery
                                </label>
                                <p style="padding: 5px">Cash on Delivery. Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="https://www.mrbakerbd.com/page/privacy-policy">Privacy Policy</a>.</p>
                            </div>
                        @endif

                        @if($settings->payment_sslc == 1)
                            <div class="box-radio">
                                <label class="radio-inline">
                                    <input name="payment_method" value="2" class="active" type="radio" {{ $settings->payment_sslc == 1 ? 'checked' : '' }}>Pay Online
                                </label>
                                <p style="padding: 5px">Pay securely by bKash, Rocket, Nagad, Debit Cards, Credit Cards, Mobile Banking (8 Brands), Internet Banking (6 Banks) through SSLCommerz. Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="https://www.mrbakerbd.com/privacy-policy">Privacy Policy</a>.</p>
                            </div>
                        @endif
                        <span class="error-message text-danger" id="error-payment_method"></span>

                        @if(collect(session('cart', []))->isEmpty())
                            <div class="alert alert-warning">
                                Your cart is empty. Please add items to your cart before proceeding to checkout.
                            </div>
                        @else
                            <h3 class="fw-medium mt-5">Cart Total</h3>
                            <div class="table-custom-responsive">
                                @php
                                    $cartSubtotal = collect(session('cart', []))->reduce(function ($carry, $item) {
                                        return $carry + ((float) $item['price'] * (int) $item['quantity']);
                                    }, 0);

                                @endphp
                                <table class="table-custom table-custom-primary table-checkout">
                                    <tbody>
                                    <tr>
                                        <td>Cart Subtotal</td>
                                        <td>{{ number_format($cartSubtotal, 2) }} Tk</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td id="shipping-cost">{{ number_format($shipping, 2) }} Tk</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td id="total-cost">{{ number_format($cartSubtotal + $shipping, 2) }} Tk</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <label class="checkbox-inline text-transform-capitalize">
                                <input id="same-address" type="checkbox" name="conditions" value="yes" required/>
                                I have read and agree to the <a href="https://www.mrbakerbd.com/terms-conditions">Terms & Conditions</a> *
                                <span class="error-message text-danger" id="error-conditions"></span>
                            </label>

                            <div class="mt-4">
                                <button type="submit" class="button button-lg button-primary button-zakaria">
                                    Confirm Order
                                </button>
                            </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </section>
@endsection

@section('page-scripts')
       <script src="{{ asset('frontend/js/checkout.js') }}"></script>
@endsection
