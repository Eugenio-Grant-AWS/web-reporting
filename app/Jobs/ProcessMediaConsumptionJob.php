<?php

namespace App\Jobs;

use App\Models\ReachExposure;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMediaConsumptionJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 600;

    private array $lineData;
    private array $fieldMap;

    /**
     * Create a new job instance.
     */
    public function __construct(array $lineData, array $fieldMap)
    {
        $this->lineData = $lineData;
        $this->fieldMap = $fieldMap;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $QuotGene = $this->lineData[$this->fieldMap['QuotGene']] ?? null;
        $QuotEdad = $this->lineData[$this->fieldMap['QuotEdad']] ?? null;
        $QuoSegur = $this->lineData[$this->fieldMap['QuoSegur']] ?? null;
        $mediaType = $this->lineData[$this->fieldMap['MediaType']] ?? null;
        $value = $this->lineData[$this->fieldMap['Value']] ?? null;
        $adjustedValue = $this->lineData[$this->fieldMap['Adjusted_value']] ?? null;

        ReachExposure::create([
            'QuotGene' => $QuotGene,
            'QuotEdad' => $QuotEdad,
            'QuoSegur' => $QuoSegur,
            'MediaType' => $mediaType,
            'Value' => $value,
            'Adjusted_value' => $adjustedValue,
        ]);
    }
}