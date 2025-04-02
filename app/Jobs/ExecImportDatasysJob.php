<?php

namespace App\Jobs;

use App\Models\Certificado;
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
        $certificado = new Certificado();
        $datasys = new DatasysService($certificado);
        $datasys->getDatasysData($this->date);
    }
}
