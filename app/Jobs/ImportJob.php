<?php

namespace App\Jobs;

use App\Mail\ImportFinishedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected ToCollection $class,protected string $file_name,protected string $file_path)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file_path = Storage::path($this->file_path . '/'. $this->file_name);
        $uploaded_file = new \Illuminate\Http\UploadedFile($file_path,$this->file_name);

        Excel::import(new $this->class,$uploaded_file);

        Mail::to(['htetshine.htetmkk@gmail.com','nokezinwai@gmail.com'])->send(new ImportFinishedMail($this->file_name));

    }

    public function tags(): array
    {
        return ['import', 'job:'.$this->file_name];
    }
}
