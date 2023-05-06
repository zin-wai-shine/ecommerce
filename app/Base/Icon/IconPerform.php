<?php

namespace App\Base\Icon;

use App\Http\Requests\IconRequest;
use App\Models\Icon;

class IconPerform
{
    public function perform(IconRequest $request,?Icon $icon=null)

    {

        $data = [
            'icon_type' => $request->icon_type,
            'icon_name' => $request->icon_name,
        ];

        return Icon::updateOrCreate(['id' => $icon?->id],$data);
    }

    public function delete(array $icons)
    {
        return Icon::destroy($icons);
    }
}
