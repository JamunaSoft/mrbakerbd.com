<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('position', 'asc')->paginate(25)->withQueryString();

        return view('backend.page.index', ['pages' => $pages]);
    }

    public function create()
    {
        return view('backend.page.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      	=> 'required|max:250',
            'content'       => 'required',
            'position'      => 'required',
        ],[
            'title.required'    => 'Title can not be empty!',
            'content.required'  => 'Content can not be empty!',
            'position.required' => 'Position can not be empty!',
        ]);

        $page = new Page;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->position = $request->position;
        $page->slug = Str::of($request->title)->slug('-');
        $page->status = $request->status;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->save();

        session()->flash('success', 'Page is added.');
        return redirect()->route('admin.pages.index');
    }

    public function edit(Page $page)
    {
        if(!is_null($page))
        {
            return view('backend.page.edit', ['page' => $page]);
        } else {
        	return redirect()->route('admin.pages.index');
        }
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title'         => 'required|max:150',
            'content'       => 'required',
            'position'      => 'required',
        ],[
            'title.required'    => 'Title can not be empty!',
            'content.required'  => 'Content can not be empty!',
            'position.required' => 'Position can not be empty!',
        ]);

        $page->title = $request->title;
        $page->content = $request->content;
        $page->position = $request->position;
        $page->slug = Str::of($request->title)->slug('-');
        $page->status = $request->status;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->save();

        session()->flash('success', 'Page is updated.');
        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page)
    {
        if(!is_null($page))
        {
            $page->delete();
        }

        session()->flash('success', 'Page is deleted!');
        return redirect()->route('admin.pages.index');
    }
}
