<?php

namespace App\Services\Auth\Admin\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductService
{
    public function upsert(Request $request,?Product $product)
    {
        $new_product = (new ProductCreateService)->createProduct($request,$product);
        return $new_product;
    }
    
    /**
     * Method show
     *
     * @param $id $id [explicite description]
     *
     * @return JsonResponse
     */
    public static function show($id)
    {
        $product = Product::where('id',$id)->with('reviews','images')->first();
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Method destroy
     *
     * @param array $ids [explicite description]
     *
     * @return JsonResponse
     */
    public static function destroy(array $ids)
    {
        Product::destroy($ids);
        return response()->json([
            'product' => "products are deleted successfully!"
        ]);
    }

}

