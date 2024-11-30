<?php

namespace App\Jobs;

use App\Services\DatasysService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExecImportDatasysJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $datasys = new DatasysService();

        $datasys->getDatasysData();
    }
}
