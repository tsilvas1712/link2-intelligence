<?php

namespace App\Console\Commands;

use App\Jobs\ExecImportDatasysJob;
use App\Models\Datasys;
use App\Models\SyncError;
use App\Services\DatasysService;
use Carbon\Carbon;
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
        /*$datasys = Datasys::query()
            ->select('Data_x0020_pedido')
            ->orderBy('Data_x0020_pedido', 'desc')
            ->limit(1)->get();*/

        $syncErrors = SyncError::query()
            ->select('date_sync')
            ->where('sync', false)
            ->get()
            ->toArray();



        //$date = Carbon::now()->format('Y-m-d');
        //$last_date = Carbon::parse($datasys[0]['Data_x0020_pedido'])->format('Y-m-d');
        //$diff = Carbon::parse($last_date)->diffInDays($date) - 1;

        /*for ($i = $diff; $i >= 1; $i--) {
            $date_to_import = Carbon::now()->subDays($i)->format('Y-m-d');
            ExecImportDatasysJob::dispatch($date_to_import);
        }*/

        $data_atual = Carbon::now()->subDays(1)->format('Y-m-d');

        array_push($syncErrors, ['date_sync' => $data_atual]);


        foreach ($syncErrors as $error) {
            $date_to_import = Carbon::parse(time: $error['date_sync'])->format('Y-m-d');
            ExecImportDatasysJob::dispatch($date_to_import);
        }
    }
}
