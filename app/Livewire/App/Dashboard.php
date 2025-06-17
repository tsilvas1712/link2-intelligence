<?php

namespace App\Livewire\App;

use App\Models\Category;
use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $ano;
    public $mes;

    public function mount(){
        $this->ano = Carbon::now()->subDay(1)->format('Y');
        $this->mes = Carbon::now()->subDay(1)->format('m');
    }

    #[Layout('components.layouts.view')]
    public function render()
    {


        $telas = Category::query()
            ->where('active', 1)
            ->orderBy('order', 'asc')
            ->get();
        return view('livewire.app.dashboard',[
            'telas' => $telas,
        ]);
    }

    public function getValores($id=null){
        if(!$id){
            return [];
        }

        $grupo = Grupo::find($id);
        $grupo_estoque=null;
        $plano_habilitado=null;
        $modalidade_venda=null;

        if($grupo->grupo_estoque) {
            $grupo_estoque = GrupoEstoque::query()
                ->whereIn('id', explode(';', $grupo->grupo_estoque))
                ->pluck('nome')
                ->toArray();
        }

        if($grupo->plano_habilitacao){
            $plano_habilitado = PlanoHabilitacao::query()
                ->whereIn('id', explode(';', $grupo->plano_habilitacao))
                ->pluck('nome')
                ->toArray();
        }

        if($grupo->modalidade_venda) {
            $modalidade_venda = ModalidadeVenda::query()
                ->whereIn('id', explode(';', $grupo->modalidade_venda))
                ->pluck('nome')
                ->toArray();
        }

        $campo_valor = $grupo->campo_valor;


            $total = Venda::query()
                ->when($grupo_estoque, function ($query) use ($grupo_estoque) {
                    return $query->whereIn('grupo_estoque', $grupo_estoque);
                })
                ->when($plano_habilitado, function ($query) use ($plano_habilitado) {
                    return $query->whereIn('plano_habilitacao', $plano_habilitado);
                })
                ->when($modalidade_venda, function ($query) use ($modalidade_venda) {
                    return $query->whereIn('modalidade_venda', $modalidade_venda);
                })
                ->whereYear('data_pedido', '=', $this->ano)
                ->whereMonth('data_pedido', '=', $this->mes)
                ->sum($campo_valor);

        return $total;



    }
}
