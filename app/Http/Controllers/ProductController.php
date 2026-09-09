<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Flavour;
use App\Models\Product;
//use App\Models\Setting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($slug)
    {
        $product = Product::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        app(\App\Services\ProductViewService::class)->record($product);

        $related_products = Product::where('status', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // exclude current product
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $best_selling = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->orderBy('views', 'desc')
            ->limit(4)
            ->get();
        $flavours = Flavour::all();

        return view('frontend.pages.product', compact('product', 'related_products','best_selling', 'flavours'));
    }

    public function category($slug)
    {
        $category = Category::where('status', 1)->where('slug', $slug)->firstOrFail();
        $categoriesList = Category::where('status', 1)->get();
        $products = $category->products()->where('status', 1)->paginate(12);
        $popularProducts = Product::where('status', 1)
            ->orderBy('views', 'desc') // or orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();



        return view('frontend.pages.category', compact('category', 'categoriesList', 'products', 'popularProducts'));
    }

    public function shop(Request $request)
    {
        $categoriesList = Category::where('status', 1)->orderBy('position', 'asc')->get();
        $query = Product::where('status', 1)->where('featured', 1)->orderBy('views', 'desc');

        if ($request->has('categories')) {
            $categoryIds = $request->get('categories');
            $query->whereIn('category_id', $categoryIds);
        }
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = $request->min_price;
            $max = $request->max_price;
            $query->whereBetween('regular_price', [$min, $max]);
        }
        $products = $query->paginate(12);
        $popularProducts = Product::where('status', 1)
            ->orderBy('views', 'desc') // or orderBy('sales_count', 'desc')
            ->limit(9)
            ->get();

        //dd($categories);

        return view('frontend.pages.shop', compact('categoriesList', 'products', 'popularProducts'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('keyword');
        $products = Product::where('status', 1)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            })
            ->paginate(12);

        return view('frontend.pages.search', compact('products'));
    }

}
