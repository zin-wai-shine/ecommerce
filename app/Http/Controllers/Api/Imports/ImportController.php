<?php

namespace App\Http\Controllers\Api\Imports;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Services\Auth\Admin\ExportAndImports\ImportExcelService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    public function __construct(protected ProductImport $import)
    {

    }

    public function products(Request $request)
    {
        $request->validate([
            'file' => 'file'
        ]);

        $count =  getCountExcel($request->file);
        $cc = (new ImportExcelService())->import($this->import,$request,$count);
        return $cc;
    }
}
