<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\ProcessarCSVService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ProcessCSV implements ShouldQueue
{
    use Queueable;

    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processar = new ProcessarCSVService($this->file);
        $processar->processar();
    }
}
