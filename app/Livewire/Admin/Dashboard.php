<?php

namespace App\Livewire\Admin;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\SyncMongo;
use App\Models\User;
use App\Models\Venda;
use App\Models\Vendedor;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;
    public $usuarios;
    public $filiais;
    public $vendedores;
    public $planos;
    public $meses;
    public $vendas;
    public $grupos_estoque;
    public $modalidades_vendas;
    public $planos_habilitados;
    public $collection_grupos_estoques;
    public $collection_modalidades_vendas;
    public $collection_planos_habilitados;


    public function mount()
    {
        $this->usuarios = User::count();
        $this->filiais = Filial::count();
        $this->vendedores = Vendedor::count();
        $this->planos = Grupo::count();
        $this->vendas = Venda::count();

        $grupos = Grupo::all();

        $grupos_estoques = [];
        $modalidades_vendas = [];
        $planos_habilitados = [];
        foreach ($grupos as $grupo) {
           $array_grupo_estoque = explode(';', $grupo->grupo_estoque);
            $array_modalidade_venda = explode(';', $grupo->modalidade_venda);
            $array_plano_habilitacao = explode(';', $grupo->plano_habilitacao);

            if (count($array_grupo_estoque) > 0 && $array_grupo_estoque[0] != '') {
                foreach ($array_modalidade_venda as $row)
            if ($row) {
                $modalidades_vendas[] = $row;
            }

                foreach ($array_plano_habilitacao as $row)
                if ($row) {
                    $planos_habilitados[] = $row;
                }

            }
           foreach ($array_grupo_estoque as $row)
            if ($row) {
                $grupos_estoques[] = $row;
            }
            }
        $collection_grupos_estoques = GrupoEstoque::query()
            ->whereNotIn('id', $grupos_estoques)
        ->get();
        $this->grupos_estoque = $collection_grupos_estoques;
        $collection_modalidades_vendas = ModalidadeVenda::query()->whereNotIn('id', $modalidades_vendas)->get();
        $this->modalidades_vendas = $collection_modalidades_vendas;
        $collection_planos_habilitados = PlanoHabilitacao::query()
            ->whereNotIn('id', $planos_habilitados)
        ->get();
        $this->planos_habilitados = $collection_planos_habilitados;

        //$this->notClassificate();
    }

    #[Computed]
    public function notClassificate(): LengthAwarePaginator
    {
        $filiais = Filial::select('id')->get();
        $aFiliais = [];
        $grupos = Grupo::all();
        $grupo_ids = [];
        foreach ($grupos as $grupo) {
            foreach (explode(';',$grupo->grupo_estoque) as $grupo_estoque) {
                $grupo_ids[] = $grupo_estoque;
            }
        }


        $g_estoque = GrupoEstoque::query()
            ->whereIn('id', $grupo_ids)
            ->get();
        dd($g_estoque);


        $grupo_estoque =$g_estoque;
        foreach ($filiais as $filial) {
            $aFiliais[] = $filial->id;
        }

        $vendas = Venda::query()
            ->whereNotIn('grupo_estoque', $grupo_estoque)
            ->whereIn('tipo_pedido', ['Venda', 'VENDA'])
            ->paginate();

        return $vendas;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }





}
