<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductControllerTest extends TestCase
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

    public function test_admin_can_view_products_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_admin_can_view_create_product_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('product.jpg');

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock' => 10,
            'sku' => 'TEST-123',
            'category_id' => $category->id,
            'featured' => true,
            'status' => true,
            'images' => [$image],
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'sku' => $data['sku'],
        ]);

        $this->assertTrue(Storage::disk('public')->exists('images/' . $image->hashName()));
    }

    public function test_admin_can_view_edit_product_form(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('updated.jpg');

        $data = [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 149.99,
            'stock' => 20,
            'sku' => 'UPD-123',
            'category_id' => $category->id,
            'featured' => false,
            'status' => true,
            'images' => [$image],
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $data['name'],
            'sku' => $data['sku'],
        ]);

        $this->assertTrue(Storage::disk('public')->exists('images/' . $image->hashName()));
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_non_admin_cannot_access_products(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.products.index'));

        $response->assertStatus(403);
    }
}
