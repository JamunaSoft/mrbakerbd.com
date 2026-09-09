<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductViewService
{
    public function record(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $product->increment('views');
            DB::table('product_views')->insert([
                'product_id' => $product->id,
                'viewed_at' => now(),
            ]);
        });
    }

    public function mostViewed(string $period): Collection
    {
        if ($period === 'all') {
            return Product::query()->select(['id', 'name', 'views'])
                ->where('views', '>', 0)->orderByDesc('views')->orderBy('id')->limit(10)->get();
        }

        $now = now();
        $start = match ($period) {
            'month' => $now->copy()->startOfMonth(),
            'year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfWeek(\Carbon\Carbon::MONDAY),
        };

        return DB::table('product_views')
            ->join('products', 'products.id', '=', 'product_views.product_id')
            ->whereBetween('viewed_at', [$start, $now])
            ->select('products.id', 'products.name')->selectRaw('COUNT(*) as views')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('views')->orderBy('products.id')->limit(10)->get();
    }
}
