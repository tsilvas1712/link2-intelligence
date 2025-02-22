<?php

namespace App\Jobs;

use App\Services\DatasysService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExecImportDatasysJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 600;
    public $date;

    /**
     * Create a new job instance.
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $datasys = new DatasysService();
        $datasys->getDatasysData($this->date);
    }
}
