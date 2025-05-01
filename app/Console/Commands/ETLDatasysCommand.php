<?php

namespace App\Console\Commands;

use App\Jobs\ETLDatasysJob;
use Illuminate\Console\Command;

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

        foreach ($mongoData as $data) {
            ETLDatasysJob::dispatch($data->data, $data->id);
        }
    }
}
