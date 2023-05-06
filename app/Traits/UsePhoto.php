<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;

trait UsePhoto
{
    protected $productImagePath;
    protected $customerImagePath;

    protected $imageSrc = "dummy.png";

    protected function getImagePath()
    {
        $this->customerImagePath = config('filePath.customer');
        $this->productImagePath = config('filePath.product');
    }

    protected function setImagePath()
    {
        $this->getImagePath();
    }

    public function getImage(mixed $image,string $type)
    {
        $type_property = $type . 'ImagePath';
        if (property_exists($this, $type_property)) {

            if(!$this->{$type_property})
            {
                $this->setImagePath();
            }
            if(strval($image) == ''){
                return $this->imageSrc;
            }
            if(!Storage::disk('local')->has($this->{$type_property} . $image)){
                return $this->imageSrc;
            }
            $imageSrc =asset(Storage::url($this->{$type_property} . $image),true);
            return $imageSrc;
        }

        throw new Exception("Invalid image type: $type");
    }


    /**
     * Method storageCreate
     *
     * @param string $storagePathName [name of the folder]
     *
     * @return string
     */
    public function storageCreate(string $storagePathName)
    {
        $path = 'public/images/data/' .$storagePathName;
        Storage::makeDirectory($path,0777,true);
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path,0777,true);
        }
        return $path;
    }

    protected function storePhotos(mixed $photos,string $path)
    {
        if(is_array($photos))
        {
            $nameCollection = [];
            foreach($photos as $key => $photo){
                $photoName = "photoImage".uniqid().'.'.$photo->getClientOriginalExtension();
                $nameCollection[$key] = $photoName;
                $photo->storeAs($path,$photoName);
            }
            return $nameCollection;
        }
        $photoName = "photoImage".uniqid().'.'.$photos->getClientOriginalExtension();
        $photos->storeAs($path,$photoName);
        return $photoName;
    }
}
