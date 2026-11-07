@extends('frontend.index')
@section('seo_title', 'Mr. Baker ' . $outlet->name . ' Outlet | Address & Contact')
@section('seo_description', 'Visit Mr. Baker ' . $outlet->name . ' outlet at ' . $outlet->address . ($outlet->phone ? ' Contact: ' . $outlet->phone . '.' : ''))
@section('seo_canonical', route('outlets.show', $outlet))
@section('content')
<section class="section section-lg bg-default text-md-start">
    <div class="container">
        <nav aria-label="Breadcrumb" class="mb-4">
            <a href="{{ route('home') }}">Home</a> / <a href="{{ route('outlets') }}">Outlets</a> / <span aria-current="page">{{ $outlet->name }}</span>
        </nav>
        <h1 class="mb-4">Mr. Baker {{ $outlet->name }} Outlet</h1>
        <div class="card card-body">
            <h2 class="h4">Address &amp; Contact</h2>
            <address class="mt-3 mb-3" style="font-style: normal">{{ $outlet->address }}</address>
            @if($outlet->phone)
                <p>Contact: <a href="tel:{{ $outlet->phone }}">{{ $outlet->phone }}</a></p>
            @endif
            <p class="mt-3"><a href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode('Mr. Baker ' . $outlet->name . ' ' . $outlet->address) }}" target="_blank" rel="noopener noreferrer">Find on Google Maps</a></p>
        </div>
        <p class="mt-4"><a href="{{ route('outlets') }}">View all outlets</a> &middot; <a href="{{ route('shop') }}">Browse cakes &amp; bakery products</a></p>
    </div>
</section>
@endsection
@section('page-scripts')
    @php
        $outletSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Bakery',
            '@id' => route('outlets.show', $outlet) . '#bakery',
            'name' => 'Mr. Baker - ' . $outlet->name,
            'url' => route('outlets.show', $outlet),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $outlet->address,
                'addressCountry' => 'BD',
            ],
        ];
        if ($outlet->phone) {
            $outletSchema['telephone'] = $outlet->phone;
        }
    @endphp
    <script type="application/ld+json">@json($outletSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endsection
