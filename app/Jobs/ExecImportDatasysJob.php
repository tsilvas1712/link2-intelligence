<?php

namespace App\Jobs;

use App\Services\DatasysService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExecImportDatasysJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 600;
    public $days = 1;

    /**
     * Create a new job instance.
     */
    public function __construct($days)
    {
    $this->days = $days;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $datasys = new DatasysService();
        $datasys->getDatasysData($this->days);

    }
}
