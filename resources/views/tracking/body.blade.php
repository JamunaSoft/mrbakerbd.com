@php($trackingSettings = $settings ?? Cache::get('settings'))
@if ($trackingSettings?->google_tag_manager_id && request()->cookie('mr_baker_tracking_consent') === 'granted')
    <!-- Google Tag Manager (noscript): only for visitors who accepted tracking. -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ $trackingSettings->google_tag_manager_id }}"
                height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe>
    </noscript>
@endif
