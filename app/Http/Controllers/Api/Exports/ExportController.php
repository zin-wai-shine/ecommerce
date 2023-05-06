<?php

namespace App\Http\Controllers\Api\Exports;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{

    public function products()
    {
        return Excel::download(new ProductExport(),'products.xlsx');
    }

    public function customers()
    {

    }

}
