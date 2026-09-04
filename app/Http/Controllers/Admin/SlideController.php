<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('position', 'asc')->paginate(25)->withQueryString();

        return view('backend.slide.index', ['slides' => $slides]);
    }

    public function create()
    {
        return view('backend.slide.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'        	=> 'required|image',
            'position'      => 'required',
        ],[
            'image.required'    => 'Image can not be empty!',
            'image.image'		=> 'Invalid image file!',
            'position.required' => 'Position can not be empty!',
        ]);

        $slide = new Slide;

        if($request->hasFile('image'))
        {
			$image = $request->file('image');
			$img = time() .'.'. $image->getClientOriginalExtension();
			$location = public_path('images/slides/' . $img);
            $manager = new ImageManager(new Driver());
            $manager->read($image)->resize(1920, 500)->save($location);

			$slide->image = $img;
        }

        $slide->text = $request->text;
        $slide->url = $request->url;
        $slide->new_window = $request->new_window;
        $slide->position = $request->position;
        $slide->status = $request->status;
        $slide->save();

        session()->flash('success', 'Slide is added.');
        return redirect()->route('admin.slides.index');
    }

    public function edit(Slide $slide)
    {
        if(!is_null($slide))
        {
            return view('backend.slide.edit', ['slide' => $slide]);
        } else {
        	return redirect()->route('admin.slides');
        }
    }

    public function update(Request $request, Slide $slide)
    {
        $request->validate([
            'image'        	=> 'nullable|image',
            'position'      => 'required',
        ],[
            'image.image'		=> 'Invalid image file!',
            'position.required' => 'Position can not be empty!',
        ]);

        if($request->hasFile('image'))
        {
			if(File::exists('images/slides/' . $slide->image)){ File::delete('images/slides/' . $slide->image); }

			$image = $request->file('image');
			$img = time() .'.'. $image->getClientOriginalExtension();
			$location = public_path('images/slides/' . $img);
			$manager = new ImageManager(new Driver());
			$manager->read($image)->resize(1920, 500)->save($location);

			$slide->image = $img;
        }

        $slide->text = $request->text;
        $slide->url = $request->url;
        $slide->new_window = $request->new_window;
        $slide->position = $request->position;
        $slide->status = $request->status;
        $slide->save();

        session()->flash('success', 'Slide is updated.');
        return redirect()->route('admin.slides.index');
    }

    public function delete(Slide $slide)
    {
       // dd($slide);
        if(!is_null($slide))
        {
            if(File::exists('images/slides/' . $slide->image)){ File::delete('images/slides/' . $slide->image); }

            $slide->delete();
        }

        session()->flash('success', 'Slide is deleted!');
        return redirect()->back();
    }
}
