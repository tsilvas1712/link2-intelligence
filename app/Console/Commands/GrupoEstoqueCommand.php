<?php

namespace App\Console\Commands;

use App\Models\GrupoEstoque;
use App\Models\Venda;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GrupoEstoqueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:grupo-estoque';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega os grupos de estoque do Datasys para o sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $grupos_estoque = GrupoEstoque::all();

        $vendas = Venda::query()
            ->select('grupo_estoque')
            ->whereNotIn('grupo_estoque', $grupos_estoque->pluck('nome')->toArray())
            ->groupBy('grupo_estoque')
            ->get();

        foreach ($vendas as $venda) {
            $grupo = new GrupoEstoque();
            $grupo->nome = Str::upper($venda->grupo_estoque);
            $grupo->descricao = 'Grupo de estoque criado automaticamente a partir DATASYS: ' . $venda->id;
            $grupo->save();

        }


    }
}
