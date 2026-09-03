<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.pages.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1|max:1000',
            'image' => 'required|string',
            'variant_id' => 'nullable|exists:product_details,id',
            'level' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'flavour' => 'nullable|string|max:255',
            'custom_note' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['id']);
        $price = $validated['price'];

        if ($product->type == 2) {
            if (!$validated['variant_id']) {
                return redirect()->back()->withErrors(['variant_id' => 'Please select a variant for this product.']);
            }
            $variant = $product->details()->findOrFail($validated['variant_id']);
            $price = $variant->special_price ?? $variant->regular_price;
        } else {
            $price = $product->special_price ?? $product->regular_price;
        }

        if ($price != $validated['price']) {
            return redirect()->back()->withErrors(['price' => 'Invalid price detected.']);
        }

        $cart = session()->get('cart', []);
        foreach ($cart as &$item) {
            $item['variant_id'] = $item['variant_id'] ?? null;
            $item['level'] = $item['level'] ?? null;
            $item['size'] = $item['size'] ?? null;
            $item['flavour'] = $item['flavour'] ?? null;
            $item['custom_note'] = $item['custom_note'] ?? null;
        }
        session()->put('cart', $cart);

        $cartKey = $product->type == 2 ? $validated['id'] . '-' . $validated['variant_id'] : $validated['id'];

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $validated['quantity'];
            if ($product->category && $product->category->is_customized) {
                $cart[$cartKey]['flavour'] = $validated['flavour'] ?? null;
                $cart[$cartKey]['custom_note'] = $validated['custom_note'] ?? null;
            }
        } else {
            $cart[$cartKey] = [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'price' => $price,
                'image' => $validated['image'],
                'quantity' => $validated['quantity'],
                'variant_id' => $product->type == 2 ? $validated['variant_id'] : null,
                'level' => $product->type == 2 ? $validated['level'] : null,
                'size' => $product->type == 2 ? $variant->size : null,
                'flavour' => ($product->category && $product->category->is_customized) ? $validated['flavour'] ?? null : null,
                'custom_note' => $product->type == 2 ? $validated['custom_note'] ?? null : null,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }



    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item removed from cart');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared');
    }

    public function update(Request $request, $id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int) $request->input('quantity', 1);
            session(['cart' => $cart]);
        }

        return redirect()->back();
    }
}
