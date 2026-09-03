    <link rel="icon" href="{{asset('images/1597305688.png')}}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700%7CLato%7CKalam:300,400,700%7CGreat+Vibes">
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.css')}} ">
    <link rel="stylesheet" href="{{asset('frontend/css/fonts.css')}} ">
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">
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
                color: #838282 !important;
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
            padding-bottom: 35px;
        }
    </style>
    <style>
    .form-input {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        background-color: #fff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        -webkit-appearance: none; /* Disable native styling on mobile */
        -moz-appearance: none;
        appearance: none;
        }

    @media (max-width: 576px) {
        .rd-navbar-aside-outer {
            padding-bottom: 0px !important;
        }


    }

    </style>
    @yield('page-styles')
