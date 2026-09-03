
<!-- Topbar for User Login -->
<div class="topbar bg-light border-bottom py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Left side: Email -->
        <div class="topbar-left text-dark">
            <i class="mdi mdi-email-outline me-1"></i>
            {{ $email }}
        </div>

        <!-- Right side: Auth links -->
        <div class="topbar-right d-flex align-items-center">
            @guest
                <a href="{{ route('login') }}" class="me-3 text-dark">Login</a>
                <a href="{{ route('register') }}" class="text-dark">Register</a>
            @else
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.dashboard') }}" class="me-3 text-dark ">My Account</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="button button-sm button-icon button-icon-left button-default-outline-3 button-zakaria"
                                style="border-radius: 20px; padding: 4px 12px; line-height: 1.2; vertical-align: middle; margin-top: 0;">
                            <span class="icon mdi mdi-logout"></span>Logout
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</div>



<div class="rd-navbar-aside-outer">

    {{-- RD Navbar Aside --}}
    <div class="rd-navbar-aside">

        {{-- Contact Info --}}
        <div class="rd-navbar-collapse">
            <div class="contacts-ruth">
                <div class="unit unit-spacing-xs-2 align-items-center">
                    <a class="brand" href="{{ route('home') }}">
                        <!-- Desktop logo -->
                        <img
                            class="brand-logo-dark d-none d-md-block"
                            src="{{ $siteLogo }}"
                            alt="Desktop Logo"
                            width="231"
                            height="231"
                            style="max-width: 200px!important; max-height: 90px!important;"
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

        </div>

        {{-- RD Navbar Panel --}}
        <div class="rd-navbar-panel">

            {{-- RD Navbar Toggle --}}
            <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap">
                <span></span>
            </button>

            {{-- RD Navbar Brand --}}
            <div class="rd-navbar-brand">
                {{--<a class="brand" href="{{ route('home') }}">
                    <img class="brand-logo-dark" src="{{ $siteLogo }}" style="max-width: 145px!important; max-height: 145px!important; margin-bottom: -35px;!important" alt="Logo" width="231" height="231" srcset="{{ $siteLogo }} 2x" />
                    <img class="brand-logo-light" src="{{ $siteLogo }}" alt="Logo" width="231" height="49" srcset="{{ $siteLogo }} 2x" />
                </a>--}}
                <a class="brand" href="{{ route('home') }}">
                    <!-- Desktop logo -->

                    <!-- Mobile logo -->
                    <img
                        class="brand-logo-dark d-block d-md-none"
                        src="{{ $mobileLogo }}"
                        alt="Mobile Logo"
                    />
                </a>
            </div>
        </div>

        {{-- Call to Action Button --}}
        <div class="rd-navbar-button">
            <a class="button button-sm button-icon button-icon-left button-default-outline-3 button-zakaria"  style="font-size: 14px!important; margin-bottom: 5px;" href="callto:{{ $phone }}">
                <span class="icon mdi mdi-phone"></span>{{ $phone }}
            </a>
        </div>

    </div>
</div>
