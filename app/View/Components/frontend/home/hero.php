<?php

namespace App\View\Components\frontend\home;

use App\Models\Slide;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $slides = Slide::where('status', 1)
            ->orderBy('position', 'desc')->take(3)
            ->get();
        //dd($slides);
        return view('components.frontend.home.hero', compact('slides'));
    }
}
