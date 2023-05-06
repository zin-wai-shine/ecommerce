<?php
namespace App\Services\Auth\Admin\Product;

use App\Http\Requests\QueryValidRequest;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ResponseJson;

class CustomerProductFetchService
{
    use ResponseJson;
    public function fetch(QueryValidRequest $request){

        $pag = request('pag') ?? 10;
        $products = $this->getProductQuery()
        ->latest('id')
        ->paginate($pag)
        ->withQueryString();

        return $this->paginateJson($products);
    }

    protected function getProductQuery()
    {

        $status = request('status') ?? '';
        $query = Product::select('products.id', 'products.title', 'products.slug', 'products.description', 'products.price', 'products.avg_rating','products.discount', 'products.product_status', 'products.category_id', 'categories.title as category',
        )
        ->when(request('search'), function ($q) {
            $search = request('search');
            $q->where('products.title', 'like', '%' . $search . '%');
        })
        ->when(request('min'), function ($q) {
            $min = request('min') ?? 0;
            $max = request('max') ?? 10000000000;
            $q->whereBetween('products.price', [$min, $max]);
        })
        ->where('products.product_status', 'like', "%$status%")
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->when(request('category'), function ($q) {
            $categorySearch = request('category');
            $category = Category::where('slug', 'like', "%$categorySearch%")->first();
            if ($category) {
                $category_id = $category->id;
                $q->where('products.category_id', '=', "$category_id");
            }
        })
        ->when(request('sort'),function($q)
        {
            $sort = request('sort');
            $order = request('order');
            $q->orderBy($sort,$order);
        })
        ->with('images');

        return $query;
    }

}
