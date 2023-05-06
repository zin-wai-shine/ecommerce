<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\AddToCart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{

    public function orders(){
        $orders = Order::with('orderItems', 'payment')->where('customer_id',Auth::id())->get();
        return response()->json(OrderResource::collection($orders));
    }

    public function request_order(){

        $carts = AddToCart::where('customer_id',Auth::id())->get();
        $orders = Order::all();
        $order = Order::latest()->first();
        /*return response()->json($order);*/

        $brand = "NOVAXECOM:";

        if($orders->isEmpty()){
            $orderNumber = "NOVAXECOM:0000001";
        }else{
            $getOrderNumber = $order->order_number;
            $getNumber = explode(':', $getOrderNumber);
            $increaseNumber = (int)$getNumber[1];
            $increaseNumber++;
            $orderNumber = $brand.str_pad($increaseNumber, strlen($getNumber[1]), '0', STR_PAD_LEFT);
        }
        // When start to order: status is 0 (requesting);
        $order = Order::create([
            'customer_id' => Auth::id(),
            'order_number' => $orderNumber
        ]);

        $totalAmount = 0;
        foreach ($carts as $cart){
            $totalPrice = $cart->item_count * $cart->price;
            $totalAmount = $totalAmount+$totalPrice;
            $item = OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' =>$cart->product_id,
                'item_count' => $cart->item_count,
                'price' => $cart->price,
                'total_price' => $totalPrice
            ]);
        }

        $payment = Payment::create([
            'order_id' => $order->order_id,
            'total_amount' => $totalAmount,
            'payment_photo' => 'payment_default.png'
        ]);

        // remove from add to cart after order those product items;
        AddToCart::whereIn('customer_id', [Auth::id()])->delete();

        return response()->json(['success' => true, 'message' => 'you was requested order. Please! check your order']);
    }

    public function confirm_order(ConfirmOrderRequest $request){
        $confirmInfo = $request->validated();
        return response()->json($confirmInfo);
    }

}
