    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @php
        $seoTitle = html_entity_decode(trim($__env->yieldContent('seo_title')), ENT_QUOTES, 'UTF-8') ?: ($settings->site_title ?? config('app.name'));
        $seoDescription = html_entity_decode(trim($__env->yieldContent('seo_description')), ENT_QUOTES, 'UTF-8') ?: ($settings->site_title ?? config('app.name'));
        $seoRobots = trim($__env->yieldContent('seo_robots')) ?: 'index, follow';
        $seoCanonical = trim($__env->yieldContent('seo_canonical')) ?: url()->current();
    @endphp
    <meta name="robots" content="{{ $seoRobots }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($seoDescription), 160) }}">
    <meta name="keywords" content="{{ trim($__env->yieldContent('seo_keywords')) }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:type" content="{{ trim($__env->yieldContent('seo_type')) ?: 'website' }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($seoDescription), 160) }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:site_name" content="{{ $settings->site_title ?? config('app.name') }}">
    <meta property="og:image" content="{{ asset('images/logo/Mr-Baker-Logo2.png') }}">
    <meta name="twitter:card" content="summary_large_image">
