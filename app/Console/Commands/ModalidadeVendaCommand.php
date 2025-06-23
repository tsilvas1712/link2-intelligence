<?php

namespace App\Console\Commands;

use App\Models\ModalidadeVenda;
use App\Models\Venda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ModalidadeVendaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:modalidade-venda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega as modalidades de venda do Datasys para o sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modalidades_venda = ModalidadeVenda::all();

        $vendas = Venda::query()
            ->select('modalidade_venda')
            ->whereNotIn('modalidade_venda', $modalidades_venda->pluck('nome')->toArray())
            ->groupBy('modalidade_venda')
            ->get();

        foreach ($vendas as $venda) {
            try{
                $modalidade = new ModalidadeVenda();
                $modalidade->nome = $venda->modalidade_venda;
                $modalidade->descricao = 'Modalidade de venda criada automaticamente a partir DATASYS: ' . $venda->id;
                $modalidade->save();
            }catch (\Exception $e){
                Log::error('Erro ao criar modalidade de venda: ' . $e->getMessage());
            }

        }
    }
}
