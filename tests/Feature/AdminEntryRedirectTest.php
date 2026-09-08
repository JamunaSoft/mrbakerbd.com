<?php

namespace Tests\Feature;

use App\Http\Middleware\SiteSettings;
use App\Http\Middleware\UpdateLastOnline;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class AdminEntryRedirectTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([SiteSettings::class, UpdateLastOnline::class]);
    }

    public function test_staff_entry_urls_redirect_to_admin_dashboard(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->shouldReceive('hasAnyRole')->andReturn(true);
        $this->actingAs($user);

        foreach (['/admin', '/login', '/admin/login'] as $url) {
            $this->get($url)->assertRedirect(route('admin.dashboard'));
        }
    }

    public function test_customer_login_redirect_stays_on_storefront(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 2;
        $user->shouldReceive('hasAnyRole')->andReturn(false);
        $this->actingAs($user);
        $this->get('/login')->assertRedirect(route('home'));
        $this->get('/admin')->assertForbidden();
    }

    public function test_guest_admin_entry_redirects_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }
}
