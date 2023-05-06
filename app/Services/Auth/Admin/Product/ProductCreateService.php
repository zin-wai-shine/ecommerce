<?php

namespace App\Services\Auth\Admin\Product;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use App\Traits\UsePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Validation\Rules\Enum;

class ProductCreateService
{
    use UsePhoto;
    public function createProduct(Request $request,?Product $product)
    {
        $validatedData = $this->validatePerMethod($request);//return std::class

        //convert std class to array
        $data = json_decode(json_encode($validatedData), true);

        if ($request->isMethod('post'))  {
            $data = [
                'title' => $validatedData->title,
                'slug' => Str::slug($validatedData->title),
                'description' => $validatedData->description,
                'price' => (int) $validatedData->price,
                'discount' => $validatedData->discount ?? null,
                'product_status' => $validatedData->product_status ?? 1,
                'category_id' => (int)$validatedData->category_id
            ];
        }

        $newProduct = Product::updateOrCreate(['id' => $product?->id],$data);
        $images = '';
        if($validatedData->product_photos)
        {
            $path = 'public/images/data/product';

            $this->storageCreate('product');

            $product_photos = $this->storePhotos($validatedData->product_photos,$path);

            $photo_collection = $this->photoArray($product_photos,$newProduct);
            ProductImage::insert($photo_collection);
            $images = "images are uploaded successfully";
        }
        $newProduct->setAttribute('images', $images);
        return $this->handleUpsertResponse($product,$newProduct);
    }


    protected function handleUpsertResponse($product,$newProduct)
    {
        if($product != null)
        {
            return response()->json([
                'product' => $newProduct->slug . ' was updated successfully!'
            ]);
        }
        return response()->json([
            'product' => $newProduct
        ]);
    }

     /**
     * Method validatePerMethod
     *
     * @param Request $request [validating request]
     *
     * @return object
     */
    protected function validatePerMethod(Request $request)
    {
        $rules = [];

        if ($request->isMethod('post'))  {
            $rules = [
                'title' => 'required|min:2',
                'price' => 'required|numeric|min:1|max:1000000000',
                'description' => 'required|min:3',
                'discount' => 'numeric|nullable',
                'product_status' => [new Enum(ProductStatusEnum::class)],
                'category_id' => 'required|numeric|exists:categories,id',
                'product_photos.*' => 'nullable|file|mimes:jpeg,png',
            ];
        }
        elseif (($request->isMethod('put') || $request->isMethod('patch') ))
        {
            $rules = [
                'title' => 'nullable|min:2',
                'price' => 'nullable|numeric|min:1|max:1000000000',
                'description' => 'nullable|min:3',
                'discount' => 'numeric|nullable',
                'product_status' => [new Enum(ProductStatusEnum::class)],
                'category_id' => 'nullable|numeric|exists:categories,id'
            ];
        }
        $validatedData = $request->validate($rules);
        return (object) $validatedData;
    }

    /**
     * Method photoArray
     *
     * @param $array $array [explicite description]
     * @param $newProduct $newProduct [explicite description]
     *
     * @return array
     */
    protected function photoArray($array,$newProduct)
    {
        $pc = [];
        foreach($array as $k=>$p)
        {
            $pc[$k] =
            [
                'path' => 'product',
                'name' => $p,
                'product_id' => $newProduct->id,
                'created_at' => now()
            ]
            ;
        }
        return $pc;
    }

}
