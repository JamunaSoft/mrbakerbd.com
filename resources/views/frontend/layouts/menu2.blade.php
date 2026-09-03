<style>
    .cart-inline-body {
        max-height: 300px; /* Adjust height to fit ~3 items */
        overflow-y: auto; /* Enable vertical scrolling */
        scrollbar-width: thin; /* For Firefox */
    }
    .cart-inline-body::-webkit-scrollbar {
        width: 6px; /* For Chrome, Safari, Edge */
    }
    .cart-inline-body::-webkit-scrollbar-thumb {
        background-color: #888; /* Scrollbar color */
        border-radius: 3px;
    }
    .cart-inline-body::-webkit-scrollbar-track {
        background: #f1f1f1; /* Scrollbar track */
    }

    .text-color-white {
        color: #fff !important;
    }

    /* Mobile styles */
    @media (max-width: 991px) {
        .text-color-white {
            color: #838282 !important;
        }
    }

    @media (min-width: 992px) {
        .text-color-white {
            color: #fff !important;
        }
    }

</style>

<div class="rd-navbar-main-outer" style="background-color: #2f3194; color: #fff;!important">
    <div class="rd-navbar-main">
        <div class="rd-navbar-nav-wrap">
            <!-- RD Navbar Nav-->
            <ul class="rd-navbar-nav">
                <li class="rd-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('home') }}">Home</a>
                </li>
                {{--<li class="rd-nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('shop') }}">Shop</a>
                </li>--}}
                @php
                    $chunks = $categories->chunk(ceil($categories->count() / 3));
                @endphp
                <li class="rd-nav-item {{ request()->routeIs('category') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('shop') }}">Categories</a>
                    <ul class="rd-menu rd-navbar-megamenu">
                        @foreach($chunks as $chunk)
                            <li class="rd-megamenu-item flex-grow-1 flex-shrink-0">
                                <ul class="rd-megamenu-list">
                                    @foreach($chunk as $category)
                                        <li class="rd-megamenu-list-item">
                                            <a class="rd-megamenu-list-link" href="{{ route('category', $category->slug) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="rd-nav-item {{ request()->routeIs('outlets') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('outlets') }}">Outlets</a>
                </li>

                <li class="rd-nav-item {{ request()->routeIs('feedback') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('feedback') }}">Feedback</a>
                </li>
                <li class="rd-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('contact') }}">Contact Us</a>
                </li>
            </ul>
        </div>
        <div class="rd-navbar-main-element">
            <!-- RD Navbar Search-->
            <div class="rd-navbar-search rd-navbar-search-3">
                <button class="rd-navbar-search-toggle rd-navbar-fixed-element-3" data-rd-navbar-toggle=".rd-navbar-search" aria-label="Search"><span></span></button>
                <form class="rd-search" action="{{ route('search') }}" method="GET">
                    <div class="form-wrap">
                        <label class="form-label" for="rd-navbar-search-form-input">   Search...</label>
                        <input class="rd-navbar-search-form-input form-input" id="rd-navbar-search-form-input" type="text" name="keyword" value="{{ request('keyword') }}" autocomplete="off"/>
                        <button class="rd-search-form-submit fl-bigmug-line-search74" type="submit"></button>
                    </div>
                </form>
            </div>
            <!-- RD Navbar Basket-->
            <div class="rd-navbar-basket-wrap">
                <button class="rd-navbar-basket fl-bigmug-line-shopping202 text-white" data-rd-navbar-toggle=".cart-inline" aria-label="Cart">
                    <span style="color: #fff">{{ collect(session('cart', []))->sum('quantity') }}</span>
                </button>

                <div class="cart-inline">
                    <div class="cart-inline-header">
                        <h5 class="cart-inline-title">In cart: <span>{{ collect(session('cart', []))->sum('quantity') }}</span> Products</h5>
                        <h6 class="cart-inline-title">
                            Total price:
                            <span>
                                {{ collect(session('cart', []))->reduce(function ($carry, $item) {
                                    return $carry + ((float) $item['price'] * (int) $item['quantity']);
                                }, 0) }} Tk
                            </span>
                        </h6>
                    </div>

                    <div class="cart-inline-body">
                        @if(collect(session('cart', []))->sum('quantity')  == 0)
                            <div class="cart-inline-item">
                                <p class="text-center text-dark">Your cart is empty</p>
                            </div>
                        @else
                            @foreach(collect(session('cart', [])) as $id => $item)
                                <div class="cart-inline-item">
                                    <div class="unit unit-spacing-sm align-items-center">
                                        <div class="unit-left">
                                            <a class="cart-inline-figure" href="{{ route('product', $item['slug']) }}">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="height:75px; width:75px;"/>
                                            </a>
                                        </div>
                                        <div class="unit-body">
                                            <h6 class="cart-inline-name">
                                                <a href="{{ route('product', $item['slug']) }}">{{ $item['name'] }}</a>
                                            </h6>
                                            @if(isset($item['variant_id']) && $item['variant_id'])
                                                <p class="text-dark">Size: {{ $item['size'] ?? 'N/A' }}</p>
                                            @endif
                                            <div>
                                                <div class="group-xs group-middle">
                                                    <div class="table-cart-stepper">
                                                        {{--<input class="form-input" type="number" value="{{ $item['quantity'] }}" min="1" readonly />--}}
                                                    </div>
                                                    <span class="cart-inline-title">
                                                        <span class="text-dark">{{ (float) $item['quantity'] }} x {{ $item['price'] }} Tk </span>
                                                        <span  class="text-dark" style=" font-weight: bold"> = {{ (float) $item['quantity'] * (int) $item['price'] }} Tk</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="cart-inline-footer">
                        <div class="group-sm">
                               <a class="button button-default-outline-2 button-zakaria" href="{{ route('cart') }}">Go to cart</a>
                            <a class="button button-primary button-zakaria" href="{{ route('checkout') }}">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
            <a class="rd-navbar-basket rd-navbar-basket-mobile fl-bigmug-line-shopping202 rd-navbar-fixed-element-2" href="{{ route('cart') }}">
                <span>{{ collect(session('cart', []))->sum('quantity') }}</span>
            </a>
        </div>
    </div>
</div>
