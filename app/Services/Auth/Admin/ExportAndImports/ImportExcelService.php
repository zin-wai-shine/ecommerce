<?php

namespace App\Services\Auth\Admin\ExportAndImports;

use App\Jobs\ImportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Str;

class ImportExcelService
{
    public function import(ToCollection $class,Request $request,int $count)
    {
        $errors =[];
        $rows = [];
        $file = $request->file;

        try {
            if($count > 1000)
            {
                $file_name = Str::replace(' ','_',$file->getClientOriginalName());
                $file_contents = file_get_contents($file);
                $storage_path = storageCreate('data/imports');
                $file->storeAs($storage_path,$file_name);
                ImportJob::dispatch(new $class,$file_name,$storage_path);

                return response()->json([
                    'message' => "We will send email you when import is finished",
                    'instruction to backend developer' => "You need to command (php artisan horizon) to start queue job; "
                ]);
            }
            Excel::import(new $class,$file);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            foreach ($failures as $key => $failure) {
                $rows[$key] = $failure->row(); // row that went wrong
                $attrs[$key] = $failure->attribute(); // either heading key (if using heading row concern) or column index

                $errors[$key] = $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

        return $this->failureControl($errors,$rows);
    }

    protected function failureControl(array $errors,array $rows) :JsonResponse
    {
        if (!empty($errors)) {
            foreach ($errors as $i => $error) {
                $ms = isset($error[0]) ? $error[0] : '';
                $row = $rows[$i] - 7;
                $error_info[] = $ms . ' at row ' . $row;
            }

            $messages = [];
            $messages[] = array_unique($error_info);

            return response()->json(['message' => $messages]);
        } else {
           return response()->json(['message' => 'Importing was success']);
        }
    }
}
