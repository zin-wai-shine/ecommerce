<?php

namespace App\Imports;

use App\Enum\ProductStatusEnum;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToCollection,WithBatchInserts,WithChunkReading,WithStartRow
{

    public function collection(Collection $rows)
    {
        foreach($rows as $k => $row)
        {
            $row = $row->toArray();
            $cate_id = Category::where('title','like','%'.$row[7].'%')->first();
            $ps = 1;
            if($row[6] == ProductStatusEnum::OUTSTOCK->toString())
            {
                $ps = 0;
            }
            else if($row[6] == ProductStatusEnum::INSTOCK->toString())
            {
                $ps = 1;
            }
            $formattedResult = number_format((float) $row[4], 2, '.', '');
            $data =[
                'title' => $row[1],
                'slug' => Str::slug($row[1]),
                'description' => $row[2],
                'price' => $row[3],
                'avg_rating' => $formattedResult,
                'discount' => $row[5],
                'product_status' => $ps,
                'category_id' => $cate_id?->id ,
            ];
            $product = Product::create($data);
        }

    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function startRow(): int
    {
        return 2; // Start reading from row 2 (ignore row 1)
    }
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'avg_rating' => ['required', 'numeric'],
        ];
    }

}
