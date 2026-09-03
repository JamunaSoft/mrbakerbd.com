<?php

namespace Tests\Unit\Services;

use App\Models\Image;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ImageUploadServiceInterface;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;
    private MockInterface&ProductRepositoryInterface $productRepository;
    private MockInterface&ImageUploadServiceInterface $imageUploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->imageUploadService = Mockery::mock(ImageUploadServiceInterface::class);
        $this->productService = new ProductService(
            $this->productRepository,
            $this->imageUploadService
        );
    }

    public function test_it_can_create_a_product(): void
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock' => 10,
            'sku' => 'TEST-123',
            'category_id' => 1,
            'featured' => true,
            'status' => true,
        ];

        $product = new Product($data);
        $product->id = 1;

        $this->productRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($product);

        $result = $this->productService->create($data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($data['name'], $result->name);
        $this->assertEquals($data['price'], $result->price);
    }

    public function test_it_can_update_a_product(): void
    {
        $product = new Product();
        $product->id = 1;

        $data = [
            'name' => 'Updated Product',
            'price' => 149.99,
        ];

        $updatedProduct = new Product($data);
        $updatedProduct->id = 1;

        $this->productRepository->shouldReceive('update')
            ->once()
            ->with($product->id, $data)
            ->andReturn($updatedProduct);

        $result = $this->productService->update($product, $data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($data['name'], $result->name);
        $this->assertEquals($data['price'], $result->price);
    }

    public function test_it_can_delete_a_product(): void
    {
        $product = new Product();
        $product->id = 1;

        $image = new Image();
        $image->path = 'test.jpg';
        $product->images = new Collection([$image]);

        $this->imageUploadService->shouldReceive('delete')
            ->once()
            ->with($image->path)
            ->andReturn(true);

        $this->productRepository->shouldReceive('delete')
            ->once()
            ->with($product->id)
            ->andReturn(true);

        $result = $this->productService->delete($product);

        $this->assertTrue($result);
    }

    public function test_it_can_get_featured_products(): void
    {
        $products = new Collection([
            new Product(['name' => 'Featured 1']),
            new Product(['name' => 'Featured 2']),
        ]);

        $this->productRepository->shouldReceive('getFeatured')
            ->once()
            ->andReturn($products);

        $result = $this->productService->getFeatured();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_it_can_get_products_by_category(): void
    {
        $categoryId = 1;
        $products = new Collection([
            new Product(['name' => 'Product 1']),
            new Product(['name' => 'Product 2']),
        ]);

        $this->productRepository->shouldReceive('getByCategory')
            ->once()
            ->with($categoryId)
            ->andReturn($products);

        $result = $this->productService->getByCategory($categoryId);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_it_can_get_product_by_slug(): void
    {
        $slug = 'test-product';
        $product = new Product(['name' => 'Test Product']);

        $this->productRepository->shouldReceive('getBySlug')
            ->once()
            ->with($slug)
            ->andReturn($product);

        $result = $this->productService->getBySlug($slug);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Test Product', $result->name);
    }

    public function test_it_can_search_products(): void
    {
        $query = 'test';
        $products = new LengthAwarePaginator(
            [new Product(['name' => 'Test Product'])],
            1,
            12
        );

        $this->productRepository->shouldReceive('search')
            ->once()
            ->with($query)
            ->andReturn($products);

        $result = $this->productService->search($query);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
    }

    public function test_it_can_get_related_products(): void
    {
        $product = new Product(['id' => 1, 'category_id' => 1]);
        $relatedProducts = new Collection([
            new Product(['name' => 'Related 1']),
            new Product(['name' => 'Related 2']),
        ]);

        $this->productRepository->shouldReceive('getRelated')
            ->once()
            ->with($product, 4)
            ->andReturn($relatedProducts);

        $result = $this->productService->getRelated($product);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_it_can_update_product_stock(): void
    {
        $productId = 1;
        $quantity = 50;

        $this->productRepository->shouldReceive('updateStock')
            ->once()
            ->with($productId, $quantity)
            ->andReturn(true);

        $result = $this->productService->updateStock($productId, $quantity);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
