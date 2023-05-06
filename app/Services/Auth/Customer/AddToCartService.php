<?php

namespace App\Services\Auth\Customer;

use App\Models\AddToCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddToCartService
{

    public function findWhere($cart_id){
        $cart = AddToCart::where('cart_id', $cart_id)->where('customer_id', Auth::id())->first();
        return $cart;
    }

    public function delete($cart_id): JsonResponse
    {
        $cart = AddToCart::where('cart_id', $cart_id)->where('customer_id', Auth::id())->first();
        $cart->delete();
        return response()->json(['success'=>true, 'message' => 'removed from add to cart']);
    }

}
