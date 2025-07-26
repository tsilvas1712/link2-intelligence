<?php

namespace App\Console\Commands;

use App\Jobs\ETLDatasysJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ETLDatasysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:etl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copia os dados do MongoDB para o PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mongoData = \App\Models\SyncMongo::query()
            ->where('migrated', false)
            ->get();

        $batchs = [];

        foreach ($mongoData as $data) {
            $job = new ETLDatasysJob($data->data, $data->id);
            $batchs[] = $job;
            //ETLDatasysJob::dispatch($data->data, $data->id)->onQueue('processing');
        }

        Bus::batch($batchs)->then(function () {
            \Artisan::call('datasys:grupo-estoque');
            \Artisan::call('datasys:modalidade-venda');
            \Artisan::call('datasys:plano-habilitacoes');
        })->dispatch();
    }
}
