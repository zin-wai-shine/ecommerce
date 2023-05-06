<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\StoreAddToCartRequest;
use App\Http\Requests\customer\UpdateAddToCartRequest;
use App\Http\Resources\AddToCartResource;
use App\Models\AddToCart;
use App\Models\Product;
use App\Services\Auth\Customer\AddToCartService;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class AddToCartController extends Controller
{

    private $addToCartService;

    public function __construct(AddToCartService $addToCartService){
        $this->addToCartService = $addToCartService;
    }


    public function index()
    {

        $carts = AddToCart::where('customer_id',Auth::id())->get();
        if($carts->isEmpty()){
            return response()->json(['success'=> true, 'message' => "It's empty"]);
        }else{
            return response()->json(AddToCartResource::collection($carts));
        }

    }


    public function store(StoreAddToCartRequest $request)
    {
        $addToCartRequest = $request->validated();
        $product = Product::where('id', $addToCartRequest['product_id'])->first();
        $addToCartRequest['product_name'] = $product->title;
        $addToCartRequest['customer_id'] = Auth::id();
        $addToCartRequest['customer_name'] = Auth::user()->full_name;
        $addToCartRequest['price'] = $product->price;
        $addToCartProduct = AddToCart::create($addToCartRequest);
        return response()->json(['success' => true, 'message' => 'product was added', 'cartProduct' => $addToCartProduct]);
    }


    public function show($cart_id)
    {
       $cart = $this->addToCartService->findWhere($cart_id);
        return response()->json(new AddToCartResource($cart));
    }


    public function update(UpdateAddToCartRequest $request, $cart_id)
    {
        // 0 is decrement and 1 is increment
        $action = $request->validated();

        $cart = $this->addToCartService->findWhere($cart_id);
        $product = Product::where('id', $cart->product_id)->where('customer_id', Auth::id())->first();

        $currentItemCount = $cart->item_count;

        if($action['action'] == 0){
            $reduceItemCount = $currentItemCount - 1;
            if($reduceItemCount < 1){
                return response()->json(['success' => false, 'message' => 'The item must be greater than or equal to 1']);
            }else{
                $cart->item_count = $reduceItemCount;
                $cart->update();
                return response()->json(['success' => true, 'cart' => new AddToCartResource($cart)]);
            }
        }else{
            $increaseItemCount = $currentItemCount + 1;
            if($increaseItemCount > $product->stock){
                return response()->json(['success' => false, 'message' => 'The item must be less than balance stock or equal']);
            }else{
                $cart->item_count = $increaseItemCount;
                $cart->update();
                return response()->json(['success' => true, 'cart' => new AddToCartResource($cart)]);
            }
        }

    }


    public function destroy($cart_id)
    {
        return $this->addToCartService->delete($cart_id);
    }
}
