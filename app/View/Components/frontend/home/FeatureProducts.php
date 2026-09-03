<?php

namespace App\View\Components\frontend\home;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeatureProducts extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $featured_products = Product::where('status', 1)->where('featured', 1)->take(12)->inRandomOrder()->get();
        return view('components.frontend.home.feature-products', compact('featured_products'));
    }
}
