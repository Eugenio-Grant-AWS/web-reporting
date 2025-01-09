<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessImportJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 1200;

    private string $file;

    /**
     * Create a new job instance.
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing file:', ['file' => $this->file]);

        $fileStream = fopen($this->file, 'r');

        $skipFirstLine = true;

        $fieldMap = [
            'QuotGene' => 0,
            'QuotEdad' => 1,
            'QuoSegur' => 2,
            'MediaType' => 3,
            'Value' => 4,
            'Adjusted_value' => 5,
        ];

        while (($line = fgetcsv($fileStream)) !== false) {
            if ($skipFirstLine) {
                $skipFirstLine = false;
                continue;
            }

            dispatch(new ProcessMediaConsumptionJob($line, $fieldMap));
        }

        fclose($fileStream);

        unlink($this->file);
    }
}