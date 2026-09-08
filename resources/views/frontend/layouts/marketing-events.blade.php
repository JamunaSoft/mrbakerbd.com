@php
    $marketingEvents = session()->pull('marketing_events', []);
    if (request()->routeIs('product') && isset($product)) {
        $marketingEvents[] = \App\Services\MarketingEvents::event('view_item', [\App\Services\MarketingEvents::productItem($product)]);
    }
    if (request()->routeIs('shop', 'category', 'search') && isset($products)) {
        $products->getCollection()->loadMissing('details');
        $items = collect($products->items())->map(fn ($item) => \App\Services\MarketingEvents::productItem($item))->all();
        $marketingEvents[] = \App\Services\MarketingEvents::event('view_item_list', $items, ['item_list_id' => request()->route()->getName()]);
    }
    if (request()->routeIs('cart', 'checkout')) {
        $items = \App\Services\MarketingEvents::cartItems(session('cart', []));
        if ($items) {
            $marketingEvents[] = \App\Services\MarketingEvents::event(request()->routeIs('cart') ? 'view_cart' : 'begin_checkout', $items);
        }
    }
    if (isset($purchaseEvent) && $purchaseEvent) {
        $marketingEvents[] = $purchaseEvent;
    }
@endphp
<script>
    (function () {
        var events = {{ Illuminate\Support\Js::from($marketingEvents) }};
        var enhanced = {{ Illuminate\Support\Js::from($settings->enhanced_conversions_enabled ? ($enhancedConversionData ?? []) : []) }};
        events.forEach(function (event) {
            window.MrBakerMarketing.push(event, event.event === 'purchase' ? enhanced : {});
        });
    })();
</script>
