@extends('frontend.index')
@section('seo_title', 'My Account | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_robots', 'noindex, nofollow')
@section('content')
<section class="breadcrumbs-custom">
    <div class="parallax-container" data-parallax-img="{{optimized_asset('frontend/images/breadcrumbs-bg.jpg')}}">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">Dashboard</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li class="active">Dashboard</li>
                </ul>
            </div>
        </div>
</section>
<section class="section section-xl bg-default text-md-start">
<div class="container py-5">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Welcome, {{ $user->name }}</h5>
            <p class="card-text">Email: {{ $user->email }}</p>
        </div>
    </div>
    <h4>Your Orders</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Order \#</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <ul class="mb-0">
                            @foreach($order->details as $detail)
                                <li>
                                    {{ $detail->product->name ?? 'N/A' }} &times; {{ $detail->quantity }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        &#2547;
                        {{
                            number_format($order->details->reduce(function($carry, $item) {
                                return $carry + ($item->price * $item->quantity);
                            }, 0), 2)
                        }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">You have no orders yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

</section>
@endsection
