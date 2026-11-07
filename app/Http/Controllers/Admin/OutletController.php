<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::orderBy('sort_order')->orderBy('id')->paginate(30);
        return view('backend.outlets.index', compact('outlets'));
    }

    public function create()
    {
        return view('backend.outlets.form', ['outlet' => new Outlet(['status' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        Outlet::create($this->validated($request));
        return redirect()->route('admin.outlets.index')->with('success', 'Outlet created successfully.');
    }

    public function edit(Outlet $outlet)
    {
        return view('backend.outlets.form', compact('outlet'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $outlet->update($this->validated($request));
        return redirect()->route('admin.outlets.index')->with('success', 'Outlet updated successfully.');
    }

    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        return redirect()->route('admin.outlets.index')->with('success', 'Outlet deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:100', 'regex:/^[0-9+()\s,\-]+$/'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);
    }
}
