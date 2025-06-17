<?php

namespace App\Console\Commands;

use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use Illuminate\Console\Command;

class PlanoHabilitacoesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:plano-habilitacoes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega os planos de habilitações do Datasys para o sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $planos_habilitacoes = PlanoHabilitacao::all();

        $vendas = Venda::query()
            ->select('plano_habilitacao')
            ->whereNotIn('plano_habilitacao', $planos_habilitacoes->pluck('nome')->toArray())
            ->groupBy('plano_habilitacao')
            ->get();

        foreach ($vendas as $venda) {
            $plano = new PlanoHabilitacao();
            $plano->nome = $venda->plano_habilitacao;
            $plano->descricao = 'Plano de habilitação criado automaticamente a partir DATASYS: ' . $venda->id;
            $plano->save();
        }
    }
}
