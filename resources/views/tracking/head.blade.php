@php
    $trackingSettings = $settings ?? Cache::get('settings');
    $trackingConfig = [
        'gtmId' => $trackingSettings?->google_tag_manager_id ?? '',
        'ga4Id' => $trackingSettings?->ga4_measurement_id ?? '',
        'adsId' => $trackingSettings?->google_ads_id ?? '',
        'adsLabel' => $trackingSettings?->google_ads_conversion_label ?? '',
        'pixelIds' => array_values(array_unique(array_filter(array_map('trim', explode(',', $trackingSettings?->meta_pixel_ids ?? ''))))),
        'delivery' => $trackingSettings?->tracking_delivery ?? 'website',
        'enhancedConversions' => (bool) ($trackingSettings?->enhanced_conversions_enabled ?? false),
    ];
@endphp
<script>window.mrBakerTrackingConfig = {{ Illuminate\Support\Js::from($trackingConfig) }};</script>
<script src="{{ asset('js/marketing.js') }}?v=1"></script>
