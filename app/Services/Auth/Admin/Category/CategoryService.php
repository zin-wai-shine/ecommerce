<?php

namespace App\Services\Auth\Admin\Category;

use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryService
{

    public static function upsert(CategoryRequest $request,?Category $category = null)
    {
        $icon_id = $request->icon_id ?? null;

        $data = [
            'title' => $request->name,
            'slug' => Str::slug($request->name),
            'icon_id' => $icon_id,
        ];

        $newCategory = Category::updateOrCreate(['id' => $category?->id],$data);


        return self::handleUpsertResponse($category,$newCategory);
    }


    protected static function handleUpsertResponse($category,$newCategory)
    {
        if($category != null)
        {
            return response()->json([
                'category' => $newCategory->slug . ' was updated successfully!'
            ]);
        }
        return response()->json([
            'category' => $newCategory
        ]);
    }

}
