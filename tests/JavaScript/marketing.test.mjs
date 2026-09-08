import test from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import fs from 'node:fs';
const code = fs.readFileSync(new URL('../../public/js/marketing.js', import.meta.url), 'utf8');
const purchase = {event: 'purchase', event_id: 'order-42', ecommerce: {transaction_id: '42', value: 600, currency: 'BDT', items: [{item_id: '7', item_name: 'Cake', price: 300, quantity: 2}]}};
const defaults = {gtmId: 'GTM-TEST123', ga4Id: 'G-TEST123', adsId: 'AW-123456', adsLabel: 'test_label', pixelIds: ['12345', '67890'], delivery: 'website', enhancedConversions: true};
function browser(config = {}, cookie = '', storage = new Map(), blockedStorage = false) {
    const scripts = [];
    const document = {cookie, referrer: 'https://example.com/?email=private', head: {appendChild: script => scripts.push(script)}, createElement: () => ({}), getElementById: () => null};
    const window = {mrBakerTrackingConfig: {...defaults, ...config}, location: {href: 'https://shop.test/order-success/42?signature=secret', protocol: 'https:', reload() { this.reloaded = true; }}, localStorage: {
        getItem(key) { if (blockedStorage) throw Error('blocked'); return storage.get(key) || null; },
        setItem(key, value) { if (blockedStorage) throw Error('blocked'); storage.set(key, value); }
    }};
    vm.runInNewContext(code, {window, document, URL, Date});
    return {window, document, scripts, api: window.MrBakerMarketing, commands: () => window.dataLayer.filter(x => x[0]).map(x => Array.from(x)), loaded() { scripts.forEach(s => s.onload?.()); }};
}
const events = (b, name) => b.commands().filter(c => c[0] === 'event' && c[1] === name);

test('rejecting consent loads no external scripts or ecommerce data', () => {
    const b = browser();
    b.api.push(purchase, {sha256_email_address: 'hashed'});
    assert.equal(b.scripts.length, 0);
    b.api.setConsent('denied');
    assert.equal(b.scripts.length, 0);
    assert.equal(b.window.dataLayer.filter(x => x.event === 'purchase').length, 0);
});
test('acceptance sends one purchase per provider and scopes enhanced data to Ads', () => {
    const b = browser();
    b.api.push(purchase, {sha256_email_address: 'hashed-email'});
    b.api.setConsent('granted'); b.loaded();
    assert.equal(events(b, 'purchase').length, 1);
    assert.equal(events(b, 'conversion').length, 1);
    assert.equal(events(b, 'purchase')[0][2].user_data, undefined);
    const cmds = b.commands();
    const conversionIndex = cmds.findIndex(c => c[1] === 'conversion');
    assert.equal(cmds[conversionIndex - 1][1], 'user_data');
    assert.equal(cmds[conversionIndex + 1][2], null);
    assert.equal(b.window.fbq.queue.filter(x => x[2] === 'PageView').length, 2);
    assert.equal(b.window.fbq.queue.filter(x => x[2] === 'Purchase').length, 2);
    b.api.push(purchase); b.api.activate();
    assert.equal(events(b, 'purchase').length, 1);
    assert.equal(events(b, 'conversion').length, 1);
    assert.equal(b.window.dataLayer.filter(x => x.event === 'purchase').length, 1);
    const page = cmds.find(c => c[0] === 'set' && typeof c[1] === 'object' && c[1].page_location);
    assert.equal(page[1].page_location, 'https://shop.test/order-success/42');
    assert.equal(page[1].page_referrer, 'https://example.com/');
});
test('GTM delivery waits for the imported tag and replays events queued before activation', () => {
    const b = browser({delivery: 'gtm'}, 'mr_baker_tracking_consent=granted');
    b.api.push(purchase);
    assert.equal(b.scripts.length, 1);
    assert.equal(events(b, 'purchase').length, 0);
    b.api.activate(); b.loaded();
    assert.equal(events(b, 'purchase').length, 1);
    assert.equal(events(b, 'conversion').length, 1);
});
test('refresh dedupe persists per destination and new destination still receives purchase', () => {
    const storage = new Map();
    const first = browser({}, 'mr_baker_tracking_consent=granted', storage);
    first.api.push(purchase); first.loaded();
    const second = browser({}, 'mr_baker_tracking_consent=granted', storage);
    second.api.push(purchase); second.loaded();
    assert.equal(events(second, 'purchase').length, 0);
    assert.equal(events(second, 'conversion').length, 0);
    assert.equal(second.window.fbq.queue.filter(x => x[2] === 'Purchase').length, 0);
    const third = browser({ga4Id: 'G-NEW123'}, 'mr_baker_tracking_consent=granted', storage);
    third.api.push(purchase); third.loaded();
    assert.equal(events(third, 'purchase').length, 1);
});
test('blocked storage does not break tracking or same-page deduplication', () => {
    const b = browser({}, 'mr_baker_tracking_consent=granted', new Map(), true);
    b.api.push(purchase); b.loaded(); b.api.push(purchase);
    assert.equal(events(b, 'conversion').length, 1);
});
test('blank settings disable all destinations', () => {
    const b = browser({gtmId: '', ga4Id: '', adsId: '', adsLabel: '', pixelIds: []}, 'mr_baker_tracking_consent=granted');
    b.api.push(purchase); b.loaded();
    assert.equal(b.scripts.length, 0);
    assert.equal(events(b, 'purchase').length, 0);
});
test('withdrawal revokes consent and prevents further events', () => {
    const b = browser({}, 'mr_baker_tracking_consent=granted');
    b.loaded(); b.api.setConsent('denied'); b.api.push(purchase);
    assert.equal(events(b, 'purchase').length, 0);
    assert.equal(b.window.location.reloaded, true);
    assert.ok(b.window.fbq.queue.some(x => x[0] === 'consent' && x[1] === 'revoke'));
});
test('failed provider load does not mark a purchase as delivered', () => {
    const storage = new Map();
    const b = browser({}, 'mr_baker_tracking_consent=granted', storage);
    b.api.push(purchase);
    assert.equal(events(b, 'purchase').length, 0);
    assert.equal(storage.has('mrb-purchase:G-TEST123:42'), false);
    assert.equal(storage.has('mrb-purchase:meta-12345:42'), false);
});
test('disabling enhanced conversions never queues user contact hashes', () => {
    const b = browser({enhancedConversions: false}, 'mr_baker_tracking_consent=granted');
    b.api.push(purchase, {sha256_email_address: 'private-hash'}); b.loaded();
    assert.equal(JSON.stringify(b.window.dataLayer).includes('private-hash'), false);
});
