<?php

namespace App\Services\Auth\Admin\Icon;

use App\Base\Icon\IconPerform;
use App\Http\Requests\IconRequest;
use App\Models\Icon;

class IconService extends IconPerform
{

    public static function upsert(IconRequest $request,?Icon $icon =null)
    {
        $icon_new = (new IconPerform)->perform($request,$icon);
        return static::handleUpsertResponse($icon,$icon_new);
    }


    public static function destroy(array $icons)
    {
        $icons = (new IconPerform)->delete($icons);
        return response()->json([
            'icon' => "icons are deleted successfully!"
        ]);
    }

    protected static function handleUpsertResponse($icon,$icon_new)
    {
        if($icon != null)
        {
            return response()->json([
                'icon' => $icon_new->icon_type . ' ' . $icon_new->icon_name . ' was updated successfully!'
            ]);
        }
        return response()->json([
            'icon' => $icon_new->icon_type . ' ' . $icon_new->icon_name . ' was created successfully!'
        ]);
    }

}
