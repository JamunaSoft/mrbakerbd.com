@extends('frontend.index')
@section('seo_title', 'Shopping Cart | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_robots', 'noindex, follow')
@section('content')
    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="{{optimized_asset('frontend/images/breadcrumbs-bg.jpg')}}">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">Cart</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li><a href="{{route('shop')}}">Shop</a></li>
                    <li class="active">Cart</li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Shopping Cart-->
    <section class="section section-xl bg-default">
        <div class="container">
            <!-- shopping-cart -->
            <div class="table-custom-responsive  d-none d-md-block">
                <table class="table-custom table-cart">
                    <thead>
                    <tr>
                        <th>Product name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(session('cart', []) as $id => $item)
                        <tr>
                            <td data-label="Product">
                                <div class="prod-card-inner">
                                    <a class="table-cart-figure" href="{{ route('product', $item['slug']) }}">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" />
                                    </a>
                                    <a class="table-cart-link" href="{{ route('product', $item['slug']) }}">{{ $item['name'] }}
                                        @if(isset($item['variant_id']) && $item['variant_id'])
                                            <p class="text-small" style="font-size:15px;">Size: {{ $item['size'] ?? 'N/A' }}</p>
                                            @if($item['level'])
                                                <p class="text-small" style="font-size:15px;">Level: {{ $item['level'] }}</p>
                                            @endif
                                        @endif
                                        @if(!empty($item['flavour']))
                                            <p class="text-small" style="font-size:15px;">
                                                Flavour: {{ is_array($item['flavour']) ? implode(',', $item['flavour']) : $item['flavour'] }}
                                            </p>
                                        @endif
                                    </a>
                                </div>
                            </td>
                            <td data-label="Price">{{ number_format((float) $item['price'], 2) }}Tk</td>
                            <td data-label="Quantity">
                                <form method="POST" action="{{ route('cart.update', $id) }}">
                                    @csrf
                                    <div class="table-cart-stepper">
                                        <input class="form-input" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="1000" onchange="this.form.submit()" />
                                    </div>
                                </form>
                            </td>
                            <td data-label="Total">{{ number_format((float) $item['price'] * (int) $item['quantity'], 2) }}Tk</td>
                            <td data-label="Action">
                                <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cart Items -->
            <div class="mobile-cart-items  d-block d-md-none">
                @forelse(session('cart', []) as $id => $item)
                    <div class="cart-item-card mb-3">
                        <div class="cart-item-header">
                            <div class="cart-item-image">
                                <a href="{{ route('product', $item['slug']) }}">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" />
                                </a>
                            </div>
                            <div class="cart-item-info">
                                <h3>
                                    <a href="{{ route('product', $item['slug']) }}" class="cart-item-title">
                                        {{ $item['name'] }}
                                    </a>
                                </h3>
                                @if(isset($item['variant_id']) && $item['variant_id'])
                                    <p class="text-small">Size: {{ $item['size'] ?? 'N/A' }}</p>
                                    @if($item['level'])
                                        <p class="text-small">Level: {{ $item['level'] }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="cart-item-details">
                            <div class="detail-item">
                                <div class="detail-label">Price</div>
                                <div class="detail-value">{{ number_format((float) $item['price'], 2) }} Tk</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Quantity</div>
                                <div class="detail-value">
                                    <form method="POST" action="{{ route('cart.update', $id) }}" class="quantity-form-inline">
                                        @csrf
                                        <input
                                            id="quantity_{{ $id }}"
                                            class="form-input quantity-input-inline"
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="1000"
                                            onchange="this.form.submit()"
                                        />
                                    </form>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Total</div>
                                <div class="detail-value">{{ number_format((float) $item['price'] * (int) $item['quantity'], 2) }} Tk</div>
                            </div>
                        </div>

                        <div class="cart-item-actions">
                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-form" onsubmit="return confirm('Remove this item?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-remove">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-cart-mobile">
                        <div class="empty-cart-icon">🛒</div>
                        <h3>Your cart is empty</h3>
                        <p>Add some products to get started!</p>
                        <a class="button button-primary" href="{{ route('shop') }}">Start Shopping</a>
                    </div>
                @endforelse
            </div>


            <!-- end cart items -->

            <div class="group-xl group-justify justify-content-center justify-content-md-between mt-4">
                <div>
                    {{-- Coupon form commented out as in original --}}
                </div>
                <div>
                    <div class="group-xl group-middle">
                        <div>
                            <div class="group-md group-middle">
                                <div class="heading-5 fw-medium text-gray-500">Total</div>
                                <div class="heading-3 fw-normal">
                                    {{
                                        number_format(collect(session('cart', []))->reduce(function ($carry, $item) {
                                            return $carry + ((float) $item['price'] * (int) $item['quantity']);
                                        }, 0), 2)
                                    }} Tk
                                </div>
                            </div>
                        </div>
                        <a class="button button-md button-link" href="{{ route('shop') }}">More Shopping</a>
                        <a class="button button-md button-secondary" href="{{ url('/category/candle-drinks-cap') }}">Add Decoration</a>
                        <a class="button button-md button-primary" href="{{ route('checkout') }}">Checkout</a>
                    </div>
                </div>
            </div>

        </div>
    </section>


@endsection
