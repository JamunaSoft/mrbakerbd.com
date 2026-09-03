<?php

namespace App\View\Components\frontend\home;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Products extends Component
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
        $categories = Category::where('status', 1)->orderBy('position', 'asc')->get();
        return view('components.frontend.home.products', compact('categories'));
    }
}
