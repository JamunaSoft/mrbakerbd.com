(function (w, d) {
    'use strict';
    if (w.MrBakerMarketing) return;
    var config = w.mrBakerTrackingConfig || {};
    var queue = [], active = false, started = false, googleReady = false, metaReady = false, gtmStarted = false;
    var cookieName = 'mr_baker_tracking_consent';
    var memory = {};
    var pixels = (config.pixelIds || []).filter(function (id, index, ids) { return /^\d+$/.test(id) && ids.indexOf(id) === index; });
    var ga4 = /^G-[A-Z0-9]+$/.test(config.ga4Id || '') ? config.ga4Id : '';
    var ads = /^AW-\d+$/.test(config.adsId || '') ? config.adsId : '';
    var label = /^[A-Za-z0-9_-]+$/.test(config.adsLabel || '') ? config.adsLabel : '';
    w.dataLayer = w.dataLayer || [];
    w.gtag = w.gtag || function () { w.dataLayer.push(arguments); };
    function choice() {
        var match = d.cookie.match(/(?:^|;\s*)mr_baker_tracking_consent=(granted|denied)(?:;|$)/);
        return match ? match[1] : null;
    }
    function consentState(value) {
        return {analytics_storage: value, ad_storage: value, ad_user_data: value, ad_personalization: value};
    }
    w.gtag('consent', 'default', consentState(choice() === 'granted' ? 'granted' : 'denied'));
    function load(src, done) {
        var script = d.createElement('script');
        script.async = true; script.src = src;
        if (done) script.onload = done;
        d.head.appendChild(script);
    }
    function safeUrl(value) {
        try { var url = new URL(value, w.location.href); return url.origin + url.pathname; }
        catch (error) { return ''; }
    }
    function keyFor(destination, event) {
        return event.event === 'purchase' ? 'mrb-purchase:' + destination + ':' + event.ecommerce.transaction_id : '';
    }
    function alreadySent(key) {
        if (!key) return false;
        if (memory[key]) return true;
        try { return Number(w.localStorage.getItem(key)) > Date.now() - 90 * 86400000; }
        catch (error) { return false; }
    }
    function mark(key) {
        if (!key) return;
        memory[key] = true;
        try { w.localStorage.setItem(key, String(Date.now())); } catch (error) { /* Never block shopping. */ }
    }
    function startGtm() {
        if (gtmStarted || !/^GTM-[A-Z0-9]+$/.test(config.gtmId || '') || choice() !== 'granted') return;
        gtmStarted = true;
        w.dataLayer.push({'gtm.start': Date.now(), event: 'gtm.js'});
        load('https://www.googletagmanager.com/gtm.js?id=' + encodeURIComponent(config.gtmId));
    }
    function startDestinations() {
        if (started || !active || choice() !== 'granted') return;
        started = true;
        if (ga4 || ads) {
            w.gtag('js', new Date());
            // Omit signed URLs and query strings from Google page metadata.
            w.gtag('set', {page_location: safeUrl(w.location.href), page_referrer: d.referrer ? safeUrl(d.referrer) : ''});
            if (ga4) w.gtag('config', ga4, {send_page_view: true});
            if (ads) w.gtag('config', ads, {allow_enhanced_conversions: !!config.enhancedConversions});
            load('https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(ga4 || ads), function () {
                googleReady = true; flush();
            });
        }
        if (pixels.length) {
            if (!w.fbq) {
                var fbq = w.fbq = function () { fbq.callMethod ? fbq.callMethod.apply(fbq, arguments) : fbq.queue.push(arguments); };
                if (!w._fbq) w._fbq = fbq;
                fbq.push = fbq; fbq.loaded = true; fbq.version = '2.0'; fbq.queue = [];
            }
            pixels.forEach(function (id) { w.fbq('init', id); w.fbq('trackSingle', id, 'PageView'); });
            load('https://connect.facebook.net/en_US/fbevents.js', function () { metaReady = true; flush(); });
        }
    }
    function dispatch(entry) {
        var event = entry.event, ecommerce = event.ecommerce;
        if (!entry.published) {
            entry.published = true;
            var dataKey = keyFor('dataLayer', event);
            if (!alreadySent(dataKey)) {
                w.dataLayer.push({ecommerce: null});
                w.dataLayer.push(event);
                mark(dataKey);
            }
        }
        if (!active) return;
        if (googleReady && ga4 && !entry.ga4) {
            entry.ga4 = true;
            var gaKey = keyFor(ga4, event);
            if (!alreadySent(gaKey)) {
                w.gtag('event', event.event, Object.assign({}, ecommerce, {send_to: ga4}));
                mark(gaKey);
            }
        }
        if (googleReady && ads && label && event.event === 'purchase' && !entry.ads) {
            entry.ads = true;
            var adsKey = keyFor(ads + '/' + label, event);
            if (!alreadySent(adsKey)) {
                if (config.enhancedConversions && entry.enhanced.sha256_email_address) w.gtag('set', 'user_data', entry.enhanced);
                w.gtag('event', 'conversion', {send_to: ads + '/' + label, value: ecommerce.value, currency: ecommerce.currency, transaction_id: ecommerce.transaction_id});
                w.gtag('set', 'user_data', null);
                mark(adsKey);
            }
        }
        var metaName = {view_item: 'ViewContent', add_to_cart: 'AddToCart', begin_checkout: 'InitiateCheckout', purchase: 'Purchase'}[event.event];
        if (metaReady && metaName && !entry.meta) {
            entry.meta = true;
            pixels.forEach(function (id) {
                var metaKey = keyFor('meta-' + id, event);
                if (alreadySent(metaKey)) return;
                var params = {
                    value: ecommerce.value, currency: ecommerce.currency, content_type: 'product',
                    content_ids: ecommerce.items.map(function (item) { return item.item_id; }),
                    contents: ecommerce.items.map(function (item) { return {id: item.item_id, quantity: item.quantity, item_price: item.price}; }),
                    num_items: ecommerce.items.reduce(function (total, item) { return total + item.quantity; }, 0)
                };
                w.fbq('trackSingle', id, metaName, params, {eventID: event.event_id});
                mark(metaKey);
            });
        }
    }
    function flush() {
        if (choice() !== 'granted') return;
        queue.forEach(dispatch);
    }
    function activate() { active = true; startDestinations(); flush(); }
    function setConsent(value) {
        if (value !== 'granted' && value !== 'denied') return;
        var previous = choice();
        d.cookie = cookieName + '=' + value + '; Path=/; Max-Age=15552000; SameSite=Lax' + (w.location.protocol === 'https:' ? '; Secure' : '');
        w.gtag('consent', 'update', consentState(value));
        var banner = d.getElementById('tracking-consent');
        if (banner) banner.hidden = true;
        if (value === 'granted') {
            startGtm();
            if (config.delivery !== 'gtm') activate();
            else { startDestinations(); flush(); }
        } else if (previous === 'granted') {
            if (w.fbq) w.fbq('consent', 'revoke');
            w.location.reload();
        }
    }
    w.MrBakerMarketing = {
        activate: activate,
        push: function (event, enhanced) {
            if (!event || !event.ecommerce || !Array.isArray(event.ecommerce.items)) return;
            queue.push({event: event, enhanced: enhanced || {}}); flush();
        },
        setConsent: setConsent,
        bindConsent: function () {
            var banner = d.getElementById('tracking-consent'), button = d.getElementById('tracking-preferences');
            if (!banner || !button) return;
            banner.hidden = choice() !== null;
            button.addEventListener('click', function () { banner.hidden = false; });
            banner.querySelectorAll('[data-tracking-choice]').forEach(function (item) {
                item.addEventListener('click', function () { setConsent(item.getAttribute('data-tracking-choice')); });
            });
        }
    };
    startGtm();
    if (config.delivery !== 'gtm') activate();
})(window, document);
