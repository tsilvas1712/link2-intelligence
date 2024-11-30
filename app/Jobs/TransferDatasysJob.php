<?php

namespace App\Jobs;

use App\Models\Import;
use App\Models\Venda;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TransferDatasysJob implements ShouldQueue
{
    use Queueable;

    private $data;
    private $import_id;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $import_id)
    {
        $this->data = $data;
        $this->import_id = $import_id;
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
            $import = Import::find($this->import_id);
            $import->transfered = true;
            $import->save();
        } catch (\Exception $e) {
            Log::error('Erro ao transferir dados: ' . $e->getMessage());
        }
    }
}
