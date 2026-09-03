<?php

namespace App\Services;

// CartService.php
class CartService
{
    public function calculateCartTotal(): float
    {
        $cartItems = session('cart', []);

        return collect($cartItems)->reduce(function ($total, $item) {
            $itemPrice = (float) $item['price'];
            $quantity = (int) $item['quantity'];

            return $total + ($itemPrice * $quantity);
        }, 0);
    }
}

// Blade template
//<div class="heading-3 fw-normal">
//    {{ number_format(app(CartService::class)->calculateCartTotal(), 2) }} Tk
//</div>
