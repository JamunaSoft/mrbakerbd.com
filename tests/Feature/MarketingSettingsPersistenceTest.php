<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\SettingController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MarketingSettingsPersistenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        \Illuminate\Support\Facades\DB::purge('sqlite');
        require_once database_path('migrations/2020_07_11_041213_create_settings_table.php');
        (new \CreateSettingsTable)->up();
        (require database_path('migrations/2026_09_08_120000_add_google_tag_manager_id_to_settings_table.php'))->up();
        (require database_path('migrations/2026_09_08_130000_add_marketing_settings.php'))->up();
        Auth::shouldReceive('user')->andReturn(new class {
            public function hasRole($role): bool { return true; }
        });
    }

    private function payload(): array
    {
        return ['name' => 'Baker', 'phone' => '123', 'email' => 'baker@example.com', 'date_format' => 'Y-m-d',
            'google_tag_manager_id' => 'GTM-NEW123', 'ga4_measurement_id' => 'G-NEW123',
            'google_ads_id' => 'AW-123456', 'google_ads_conversion_label' => 'Label_123',
            'meta_pixel_ids' => '123456,789012', 'tracking_delivery' => 'gtm', 'enhanced_conversions_enabled' => '1'];
    }

    public function test_settings_persist_and_invalidate_both_caches_and_can_be_disabled(): void
    {
        $setting = new Setting;
        Cache::put('settings', 'stale');
        Cache::put('settings_data', 'stale');
        (new SettingController)->update(new Request($this->payload()), $setting);
        $this->assertSame('G-NEW123', $setting->fresh()->ga4_measurement_id);
        $this->assertSame('123456,789012', $setting->fresh()->meta_pixel_ids);
        $this->assertTrue((bool) $setting->fresh()->enhanced_conversions_enabled);
        $this->assertNull(Cache::get('settings'));
        $this->assertNull(Cache::get('settings_data'));
        $payload = array_merge($this->payload(), ['ga4_measurement_id' => '', 'google_tag_manager_id' => '', 'google_ads_id' => '', 'google_ads_conversion_label' => '', 'meta_pixel_ids' => '', 'tracking_delivery' => 'website']);
        unset($payload['enhanced_conversions_enabled']);
        (new SettingController)->update(new Request($payload), $setting);
        $this->assertSame('', $setting->fresh()->google_tag_manager_id);
        $this->assertSame('', $setting->fresh()->meta_pixel_ids);
        $this->assertFalse((bool) $setting->fresh()->enhanced_conversions_enabled);
    }

    public function test_gtm_mode_requires_a_container(): void
    {
        $payload = array_merge($this->payload(), ['google_tag_manager_id' => '']);
        try {
            (new SettingController)->update(new Request($payload), new Setting);
            $this->fail('Missing container was accepted');
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->assertArrayHasKey('google_tag_manager_id', $exception->errors());
        }
    }
}
