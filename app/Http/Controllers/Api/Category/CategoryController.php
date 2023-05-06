<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\Auth\Admin\Category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
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

    public function upsert(CategoryRequest $request,?Category $category = null)
    {
        $freshCategory = CategoryService::upsert($request,$category);
        return $freshCategory;
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids.*' => 'required|numeric|exists:categories,id'
        ]);

        $ids = $request->ids;
        Category::destroy($ids);
        return response()->json([
            'category' => "Categories are deleted successfully!"
        ]);
    }

}
