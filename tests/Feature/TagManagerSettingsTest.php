<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\SettingController;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class TagManagerSettingsTest extends TestCase
{
    public function test_saved_container_is_rendered_in_both_snippets(): void
    {
        config(['services.google.tag_manager_id' => 'GTM-OLD123']);
        $settings = new Setting;
        $settings->google_tag_manager_id = 'GTM-MKFNFP57';

        request()->cookies->set('mr_baker_tracking_consent', 'granted');
        foreach (['head', 'body'] as $part) {
            $html = Blade::render(file_get_contents(resource_path("views/frontend/layouts/analytics-{$part}.blade.php")), compact('settings'));
            $this->assertStringContainsString('GTM-MKFNFP57', $html);
            $this->assertStringNotContainsString('GTM-OLD123', $html);
        }
    }

    public function test_blank_and_unconfigured_ignore_environment_ids(): void
    {
        config(['services.google.tag_manager_id' => 'GTM-OLD123', 'services.google.analytics_id' => null]);
        $settings = new Setting;
        $this->assertSame('', $settings->google_tag_manager_id);
        $settings->google_tag_manager_id = '';

        foreach (['head', 'body'] as $part) {
            $html = Blade::render(file_get_contents(resource_path("views/frontend/layouts/analytics-{$part}.blade.php")), compact('settings'));
            $this->assertStringNotContainsString('googletagmanager.com', $html);
        }
    }

    public function test_non_admin_cannot_update_settings(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new class {
            public function hasRole($role): bool { return false; }
        });
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(0);
        (new SettingController)->update(new Request, new Setting);
    }

    public function test_invalid_container_is_rejected(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new class {
            public function hasRole($role): bool { return true; }
        });
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new SettingController)->update(new Request([
            'name' => 'Mr Baker', 'phone' => '123', 'email' => 'test@example.com',
            'google_tag_manager_id' => '<script>alert(1)</script>', 'tracking_delivery' => 'website',
        ]), new Setting);
    }
}
