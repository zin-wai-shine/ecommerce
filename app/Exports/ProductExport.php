<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ProductExport implements FromView,WithColumnWidths
{
    protected $count;

    public $timeout = 600;

    public function __construct(?int $count = null)
    {
        $this->count = $count;
    }
   /**
     * @return View
     */
    public function view():View
    {
        $product =Product::latest('id')->limit($this->count)->with('category','reviews')->get();
        if($this->count == null)
        {
         $product =Product::latest('id')->with('category','reviews')->get();
        }
        return view('exports.products',[
            'products' => $product,
        ]);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 45,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 35,
            'H' => 35,
        ];
    }

}
