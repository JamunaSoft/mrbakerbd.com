<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\CategoryService;
use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $service;
    private CategoryRepositoryInterface $repository;
    private ImageUploadService $imageUploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->imageUploadService = Mockery::mock(ImageUploadService::class);
        $this->service = new CategoryService($this->repository, $this->imageUploadService);
    }

    public function test_it_can_create_a_category(): void
    {
        $data = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'position' => 1,
            'status' => true,
        ];

        $category = new Category($data);
        $category->id = 1;

        $this->repository->expects('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($data) {
                return $arg['name'] === $data['name']
                    && $arg['description'] === $data['description']
                    && $arg['position'] === $data['position']
                    && $arg['status'] === $data['status']
                    && isset($arg['slug']);
            }))
            ->andReturn($category);

        $result = $this->service->create($data);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($data['name'], $result->name);
    }

    public function test_it_can_update_a_category(): void
    {
        $id = 1;
        $data = [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
        ];

        $category = new Category([
            'name' => 'Old Category',
            'description' => 'Old Description',
        ]);
        $category->id = $id;

        $this->repository->expects('find')
            ->once()
            ->with($id)
            ->andReturn($category);

        $this->repository->expects('update')
            ->once()
            ->with($id, Mockery::on(function ($arg) use ($data) {
                return $arg['name'] === $data['name']
                    && $arg['description'] === $data['description']
                    && isset($arg['slug']);
            }))
            ->andReturn(true);

        $result = $this->service->update($id, $data);

        $this->assertTrue($result);
    }

    public function test_it_can_delete_a_category(): void
    {
        $id = 1;
        $category = new Category([
            'name' => 'Test Category',
            'icon' => 'test-icon.jpg',
            'image' => 'test-image.jpg',
        ]);
        $category->id = $id;

        $this->repository->expects('find')
            ->once()
            ->with($id)
            ->andReturn($category);

        $this->imageUploadService->expects('delete')
            ->twice()
            ->andReturn(true);

        $this->repository->expects('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->service->delete($id);

        $this->assertTrue($result);
    }

    public function test_it_can_get_root_categories(): void
    {
        $categories = new Collection([
            new Category(['name' => 'Category 1']),
            new Category(['name' => 'Category 2']),
        ]);

        $this->repository->expects('getRootCategories')
            ->once()
            ->andReturn($categories);

        $result = $this->service->getRootCategories();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
