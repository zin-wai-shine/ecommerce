<?php

namespace App\Http\Controllers\Api\Icon;

use App\Http\Controllers\Controller;
use App\Http\Requests\IconRequest;
use App\Models\Icon;
use App\Services\Auth\Admin\Icon\IconService;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IconController extends Controller
{
    use ResponseJson;
    public function index()
    {
        $icons = Icon::getIcon()->latest('id')->get();
        return $this->responseJson([
            'icons' => $icons,
        ]);
    }

    public function upsert(IconRequest $request,?Icon $icon=null)
    {
        $freshIcon = IconService::upsert($request,$icon);
        return $freshIcon;
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids.*' => 'required|numeric|exists:categories,id'
        ]);
        return IconService::destroy($request->ids);
    }
}
