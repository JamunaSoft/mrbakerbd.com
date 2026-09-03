<style>
    .cart-inline-body {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .cart-inline-body::-webkit-scrollbar {
        width: 6px;
    }
    .cart-inline-body::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 3px;
    }
    .cart-inline-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .text-color-white {
        color: #fff !important;
    }
    @media (max-width: 991px) {
        .text-color-white {
            color: #ffffff !important;
        }
        .rd-navbar-fixed .rd-megamenu-list > li > a, .rd-navbar-fixed .rd-navbar-dropdown > li > a{
            color: #ffffff !important;
        }
    }
    @media (min-width: 992px) {
        .text-color-white {
            color: #fff !important;
        }
    }
    .user-dropdown {
        min-width: 160px;
        z-index: 999;
        margin-top: 22px;
    }
    .user-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }
    .user-dropdown.show {
        display: block !important;
    }
    .mobile-auth-btns a.btn {
        color: #2f3194 !important;
        background-color: #fff !important;
        border: 1px solid #2f3194;
        font-weight: 600;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        padding: 8px 12px;
        border-radius: 5px;
        text-align: center;
    }
    .mobile-auth-btns a.btn:last-child {
        margin-bottom: 0;
    }
    .phone-call-desktop {
        color: #fff;
        font-weight: 600;
        margin-left: 15px;
        display: flex;
        align-items: center;
        font-size: 15px;
        text-decoration: none;
    }
    .phone-call-desktop:hover {
        color: #ddd;
    }
    .rd-nav-item.d-block.d-md-none.mt-2 a.btn {
        font-weight: 600;
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
    }
    .rd-navbar-aside-outer {
        padding-bottom: 60px;
    }
    @media (max-width: 767px) {
        .hide-on-mobile {
            display: none;
        }
    }

</style>

<style>
    /* Default images */
    .icon-hover.phone img {
        content: url("{{ asset('images/icon/phone.png') }}");
    }

    .icon-hover.facebook img {
        content: url("{{ asset('images/icon/facebook.png') }}");
    }

    .icon-hover.twitter img {
        content: url("{{ asset('images/icon/x.png') }}");
    }

    .icon-hover.instagram img {
        content: url("{{ asset('images/icon/instagram.png') }}");
    }

    .icon-hover.youtube img {
        content: url("{{ asset('images/icon/youtube.png') }}");
    }

    /* Hover images */
    .icon-hover.phone:hover img {
        content: url("{{ asset('images/icon/phone-2.png') }}");
    }

    .icon-hover.facebook:hover img {
        content: url("{{ asset('images/icon/facebook-2.png') }}");
    }

    .icon-hover.twitter:hover img {
        content: url("{{ asset('images/icon/x-2.png') }}");
    }

    .icon-hover.instagram:hover img {
        content: url("{{ asset('images/icon/instagram-2.png') }}");
    }

    .icon-hover.youtube:hover img {
        content: url("{{ asset('images/icon/youtube-2.png') }}");
    }
</style>

<div class="rd-navbar-aside-outer" style="background-color: #2f3194;">
    <div class="rd-navbar-aside">
        <div class="rd-navbar-collapse">
            <div class="contacts-ruth">
            </div>

        </div>

        <div class="rd-navbar-panel">

            <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap">
                <span></span>
            </button>

            <div class="rd-navbar-brand">
                <a class="brand" href="{{ route('home') }}">
                    <!-- Desktop logo -->
                    <img
                        class="brand-logo-dark d-none d-md-block"
                        src="{{ $siteLogo }}"
                        alt="Desktop Logo"
                        style="max-width: 220px!important; max-height: 220px!important;  margin-bottom: -120px;!important"
                    />

                    <!-- Mobile logo -->
                    <img
                        class="brand-logo-dark d-block d-md-none"
                        src="{{ $mobileLogo }}"
                        alt="Mobile Logo"
                    />
                </a>
            </div>
        </div>
        <div class="rd-navbar-button mt-5">
        </div>

    </div>
</div>
<div class="rd-navbar-main-outer" style="background-color: #2f3194; color: #fff;!important; border-top: 1px solid #2f3194;">
    <div class="rd-navbar-main">
        <div class="rd-navbar-nav-wrap">
            <ul class="rd-navbar-nav">
                <li class="rd-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('home') }}">Home</a>
                </li>
                <li class="rd-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('about') }}">About Us</a>
                </li>

                @php
                    $chunks = $categories->whereNull('parent_id')->chunk(ceil($categories->whereNull('parent_id')->count() / 3));
                @endphp
                <li class="rd-nav-item {{ request()->routeIs('category') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('shop') }}">Categories</a>
                    <ul class="rd-menu rd-navbar-megamenu two-columns">
                        @foreach($chunks as $chunk)
                            <li class="rd-megamenu-item">
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
                    <a class="rd-nav-link text-color-white" href="{{ route('outlets') }}">Our Outlets</a>
                </li>
                <li class="rd-nav-item {{ request()->routeIs('feedback') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('feedback') }}">Feedback</a>
                </li>
                <li class="rd-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <a class="rd-nav-link text-color-white" href="{{ route('contact') }}">Contact Us</a>
                </li>

                <li class="rd-nav-item">
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
                    <a class="rd-navbar-basket rd-navbar-basket-mobile fl-bigmug-line-shopping202 rd-navbar-fixed-element-2"  href="{{ route('cart') }}">
                        <span>{{ collect(session('cart', []))->sum('quantity') }}</span>
                    </a>
                </li>

                <li class="rd-nav-item rd-navbar--has-dropdown rd-navbar-submenu hide-on-mobile"  style="margin-left: 10px; position: relative;">
                    <a class="rd-nav-link " href="#"> <span class="rd-navbar-basket fl-bigmug-line-user143 text-white"></span></a>
                    <span class="rd-navbar-submenu-toggle"></span>
                    <!-- RD Navbar Dropdown-->
                    <ul class="rd-menu rd-navbar-dropdown">
                        @auth
                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="{{ route('user.dashboard') }}">👤 Profile</a></li>
                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}">🔓 Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                        @else
                            <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="{{ route('login') }}">🔐 Login</a></li>
                            <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="{{ route('register') }}">📝 Register</a></li>
                        @endauth
                    </ul>

                </li>

                <li class="rd-nav-item "  style="margin-left: 15px; position: relative;">
                    {{--<a href="tel:+8801712969807" class="phone-call-desktop d-none d-lg-flex" aria-label="Call us">📞 +8801712969807</a>--}}
                    <a href="tel:+8801712969807" class="icon-hover phone">
                        <img src="{{ asset('images/icon/phone.png') }}" class="rounded" alt="">
                    </a>

                    <a target="_blank" href="https://www.facebook.com/mrbakerbangladesh" class="icon-hover facebook">
                        <img src="{{ asset('images/icon/facebook.png') }}" class="rounded" alt="">
                    </a>

                    <a target="_blank" href="https://twitter.com/mrbakerbangladesh" class="icon-hover twitter">
                        <img src="{{ asset('images/icon/x.png') }}" class="rounded" alt="">
                    </a>

                    <a target="_blank" href="https://www.instagram.com/mrbakerbangladesh" class="icon-hover instagram">
                        <img src="{{ asset('images/icon/instagram.png') }}" class="rounded" alt="">
                    </a>

                    <a target="_blank" href="https://www.youtube.com/@mrbakerbd" class="icon-hover youtube">
                        <img src="{{ asset('images/icon/youtube.png') }}" class="rounded" alt="">
                    </a>
                </li>

                {{-- Phone number link visible on mobile --}}


                {{--<li class="rd-nav-item" style="margin-left: 15px; position: relative;">
                    <div class="rd-navbar-basket-wrap position-relative d-flex align-items-center">
                        --}}{{--<button class="rd-navbar-basket fl-bigmug-line-user143 text-white"
                                data-rd-navbar-toggle=".user-dropdown"
                                aria-label="User"
                                type="button">
                        </button>--}}{{--

                        --}}{{-- Phone number link visible on desktop --}}{{--
                        --}}{{--<a href="tel:01712969807" class="phone-call-desktop d-none d-lg-flex" aria-label="Call us">
                            📞 01712969807
                        </a>--}}{{--

                        <div class="user-dropdown position-absolute bg-white text-dark shadow rounded py-2 px-3 d-none" style="right: 0; top: 100%;">
                            @auth
                                <a href="{{ route('user.dashboard') }}" class="dropdown-item d-block py-1 px-2 text-dark">
                                    👤 Profile
                                </a>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="dropdown-item d-block py-1 px-2 text-dark">
                                    🔓 Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="dropdown-item d-block py-1 px-2 text-dark">
                                    🔐 Login
                                </a>
                                <a href="{{ route('register') }}" class="dropdown-item d-block py-1 px-2 text-dark">
                                    📝 Register
                                </a>
                            @endauth
                        </div>

                    </div>
                </li>--}}

                {{-- MOBILE ONLY: Login/Register or Profile/Logout BELOW menu --}}
                <li class="rd-nav-item d-block d-md-none mt-3 mobile-auth-btns">
                    <div class="text-center">
                        @auth
                            <a href="{{ route('user.dashboard') }}" class="btn">Profile</a>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();"
                               class="btn">Logout</a>
                            <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn">Login</a>
                            <a href="{{ route('register') }}" class="btn">Register</a>
                        @endauth
                    </div>
                </li>

                {{-- MOBILE ONLY: Phone Call Button --}}
                <li class="rd-nav-item d-block d-md-none mt-2">
                    <div class="text-center">
                        <a href="tel:01712969807" class="btn btn-sm btn-primary w-100" aria-label="Call us">
                            📞 Call Us: 01712969807
                        </a>
                    </div>
                </li>

            </ul>
        </div>

        <div class="rd-navbar-main-element">
            <div class="rd-navbar-basket-wrap d-block d-md-none">
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
                                                        <input class="form-input" type="number" value="{{ $item['quantity'] }}" min="1" readonly />
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
            <a class="rd-navbar-basket rd-navbar-basket-mobile fl-bigmug-line-shopping202 text-white rd-navbar-fixed-element-2" href="{{ route('cart') }}">
                <span>{{ collect(session('cart', []))->sum('quantity') }}</span>
            </a>
        </div>
    </div>
</div>
<div style="height: 4px; background-color: #EFBF04; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('[data-rd-navbar-toggle=".user-dropdown"]');
        const dropdown = document.querySelector('.user-dropdown');
        if (toggleBtn && dropdown) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    });
</script>
