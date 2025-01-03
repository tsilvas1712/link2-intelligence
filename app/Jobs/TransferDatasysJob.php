<?php

namespace App\Jobs;

use App\Models\Datasys;
use App\Models\Import;
use App\Models\Venda;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TransferDatasysJob implements ShouldQueue
{
    use Queueable;

    private $data;
    private $datasys_id;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $datasys_id)
    {
        $this->data = $data;
        $this->datasys_id = $datasys_id;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->transfer($this->data);
    }

    public function transfer()
    {
        try {
            Venda::create($this->data);
            $datasys = Datasys::query()->where('datasys_id', $this->datasys_id)->first();
            $datasys->transfered = true;
            $datasys->save();
        } catch (\Exception $e) {
            Log::error('Erro ao transferir dados: ' . $e->getMessage());
        }
    }
}
