<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminRole = Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($this->adminRole);
    }

    public function test_admin_can_view_categories_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.categories.index'))
            ->assertStatus(200)
            ->assertViewIs('admin.categories.index');
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.categories.create'))
            ->assertStatus(200)
            ->assertViewIs('admin.categories.create');
    }

    public function test_admin_can_create_category(): void
    {
        $data = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'position' => 1,
            'status' => true,
            'icon' => UploadedFile::fake()->image('icon.jpg'),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), $data)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => $data['name'],
            'description' => $data['description'],
            'position' => $data['position'],
            'status' => $data['status'],
        ]);

        $this->assertTrue(Storage::disk('public')->exists('categories/' . $data['icon']->hashName()));
        $this->assertTrue(Storage::disk('public')->exists('categories/' . $data['image']->hashName()));
    }

    public function test_admin_can_view_edit_category_form(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.categories.edit', $category))
            ->assertStatus(200)
            ->assertViewIs('admin.categories.edit');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
            'position' => 2,
            'status' => false,
        ];

        $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), $data)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'position' => $data['position'],
            'status' => $data['status'],
        ]);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_non_admin_cannot_access_categories(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.categories.index'))
            ->assertStatus(403);
    }
}
