<?php

namespace App\Console\Commands;

use App\Jobs\ExecImportDatasysJob;
use App\Services\DatasysService;
use Illuminate\Console\Command;

class ImportDataSysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar Dados da API DataSys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($i = 30; $i >=1; $i--) {

            ExecImportDatasysJob::dispatch($i);
        }

    }
}
