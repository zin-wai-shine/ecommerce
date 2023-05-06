<?php

namespace App\Http\Controllers\Api\CustomerFetch;

use App\Http\Controllers\Controller;
use App\Http\Requests\QueryValidRequest;
use App\Models\Icon;
use App\Models\Product;
use App\Services\Auth\Admin\Product\CustomerProductFetchService;
use App\Services\Auth\Admin\Product\ProductService;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FetchController extends Controller
{
    use ResponseJson;
    public function products(QueryValidRequest $request)
    {
        $products = (new CustomerProductFetchService)->fetch($request);
        return $products;
    }

    public function showProduct(Product $product)
    {
        $fresh_product = ProductService::show($product->id);
        return $fresh_product;
    }

    public function categories()
    {
        $categories = DB::table('categories as c')
        ->select('c.id', 'c.slug', 'icons.icon_type', 'icons.icon_name', DB::raw('COUNT(products.id) as product_count'))
        ->leftJoin('icons', 'icons.id', '=', 'c.icon_id')
        ->rightJoin('products', 'products.category_id', '=', 'c.id')
        ->groupBy('c.id')
        ->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function icons()
    {
        $icons = Icon::getIcon()->latest('id')->get();
        return $this->responseJson([
            'icons' => $icons,
        ]);
    }
}
