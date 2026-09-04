<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Models\Flavour;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    public function index(): View
    {
        $categories = Category::query()
            ->with(['iconImage', 'parent'])
            ->latest('id')
            ->paginate(25)
            ->withQueryString();
        return view('backend.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $categories = $this->categoryService->getAll();
        return view('backend.categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
       // dd($request->all());
        $this->categoryService->create($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category): View
    {
        return view('backend.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $categories = $this->categoryService->getAll();
        return view('backend.categories.edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->update($category->id, $request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->delete($category->id);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    public function flavours(): View
    {
        $flavours = Flavour::all();
        return view('backend.categories.flavours', compact('flavours'));
    }
    public function createFlavour(): View
    {
        return view('backend.categories.create_flavour');
    }

    public function storeFlavour(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Flavour::create($request->only('name'));

        return redirect()->route('admin.flavour.index')
            ->with('success', 'Flavour created successfully.');
    }
    public function destroyFlavour(Flavour $flavour): RedirectResponse
    {
        $flavour->delete();
        return redirect()->route('admin.flavour.index')
            ->with('success', 'Flavour deleted successfully.');
    }
}
