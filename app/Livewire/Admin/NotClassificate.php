<?php

namespace App\Livewire\Admin;

use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;


class NotClassificate extends Component
{
    use WithPagination;

    public $data_table;
    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'nome', 'label' => 'Nome'],
        ['key' => 'descricao', 'label' => 'Descrição'],
        ['key' => 'created_at', 'label' => 'Criado em'],
    ];


    public function mount($slug)
    {
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

        switch ($slug) {
            case 'grupo_estoque':
                $this->data_table = $this->gruposEstoque($grupos_estoques);
                break;
            case 'modalidades_vendas':
                $this->data_table = $this->modalidadesVendas($modalidades_vendas);
                break;
            case 'planos_habilitados':
                $this->data_table = $this->planosHabilitados($planos_habilitados);
                break;
            default:
                $this->data_table = null;
                break;
        }



    }
    public function render()
    {
        return view('livewire.admin.not-classificate');
    }


    public function gruposEstoque($grupos_estoques_id)
    {
        return GrupoEstoque::query()->whereNotIn('id', $grupos_estoques_id)->get();
    }


    public function modalidadesVendas($modalidades_vendas_id)
    {
        return ModalidadeVenda::query()
            ->whereNotIn('id', $modalidades_vendas_id)
            ->get();
    }


    public function planosHabilitados($planos_habilitados_id)
    {
        return PlanoHabilitacao::query()
            ->whereNotIn('id', $planos_habilitados_id)
            ->get();
    }
}
