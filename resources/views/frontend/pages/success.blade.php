@extends('frontend.index')
@section('seo_title', 'Order Confirmation | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_robots', 'noindex, nofollow')
@section('content')
    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="{{optimized_asset('frontend/images/breadcrumbs-bg.jpg')}}">
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
    <section class="section section-sm section-first bg-default text-md-start ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-success" role="alert">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <p>Your order has been successfully placed.</p>
                       {{-- <p class="mb-0">Order ID: {{ $order->id }}</p>--}}
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return to Home</a>
                </div>
            </div>
        </div>

    </section>



@endsection
