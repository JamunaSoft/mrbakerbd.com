<?php

namespace Tests\Feature;

use App\Http\Middleware\SiteSettings;
use App\Http\Middleware\UpdateLastOnline;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OutletTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware();
        (require database_path('migrations/2026_11_07_000001_create_outlets_table.php'))->up();
    }

    public function test_existing_outlets_are_preserved_and_public_list_is_active_and_ordered(): void
    {
        $this->assertSame(31, Outlet::count());
        $this->assertSame(30, Outlet::where('status', true)->count());
        Outlet::query()->delete();
        Outlet::create(['name' => 'Later', 'address' => 'Road 2', 'sort_order' => 20, 'status' => true]);
        Outlet::create(['name' => 'Hidden', 'address' => 'Road 3', 'sort_order' => 0, 'status' => false]);
        Outlet::create(['name' => 'First', 'address' => 'Road 1', 'sort_order' => 1, 'status' => true]);
        $view = (new \App\Http\Controllers\PageController)->outlets();
        $this->assertSame(['First', 'Later'], $view->getData()['outlets']->pluck('name')->all());
    }

    public function test_admin_can_create_update_and_delete_outlets_and_invalid_data_is_rejected(): void
    {
        $data = ['name' => 'New outlet', 'address' => 'Dhaka', 'phone' => '01955-578845', 'status' => '1', 'sort_order' => 2];
        $this->post('/admin/outlets', $data)->assertRedirect(route('admin.outlets.index'));
        $outlet = Outlet::where('name', 'New outlet')->firstOrFail();
        $this->put('/admin/outlets/'.$outlet->id, array_merge($data, ['name' => 'Updated', 'status' => '0']))->assertRedirect(route('admin.outlets.index'));
        $this->assertFalse($outlet->fresh()->status);
        $this->assertSame('Updated', $outlet->fresh()->name);
        $this->post('/admin/outlets', array_merge($data, ['name' => '', 'sort_order' => -1]))->assertSessionHasErrors(['name', 'sort_order']);
        $this->delete('/admin/outlets/'.$outlet->id)->assertRedirect(route('admin.outlets.index'));
        $this->assertNull($outlet->fresh());
    }
}
