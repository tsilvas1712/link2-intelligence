<?php

namespace App\Livewire\Admin\Group;

use App\Models\Category;
use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use Livewire\Component;

class Criar extends Component
{
    public $nome;
    public $descricao;
    public $grupo_estoque;
    public $campo_valor;
    public $modalidades_vendas;
    public $planos_habilitados;
    public $selected_category = null;

    public $categories;

    public $choice_grupo_estoque = [
        'id' => null,
        'name' => null,
    ];
    public $selected_grupo_estoque = [];

    public $selected_plano_habilitados = [];

    public $selected_modalidade_vendas = [];

    public $selected_campo_referencia;
    public $campos_referencia = [];

    public $id;
    public $active = false;

    public function mount($id = null)
    {
        $this->id = null;
        if ($id !== null) {
            $this->id = $id;
            $grupo = Grupo::find($id);
            ds($grupo);
            $this->nome = $grupo->nome;
            $this->descricao = $grupo->descricao;
            $this->active = $grupo->principal;
            $this->grupo_estoque = $grupo->grupo_estoque;
            $this->selected_category = $grupo->category_id;
            $this->campo_valor = $grupo->campo_valor;
            $this->modalidades_vendas = $grupo->modalidade_venda;
            $this->planos_habilitados = $grupo->plano_habilitacao;
            $this->selected_grupo_estoque =$grupo->grupo_estoque ? explode(';', $grupo->grupo_estoque): [];
            $this->selected_modalidade_vendas = $grupo->modalidade_venda ? explode(';', $grupo->modalidade_venda): [];
            $this->selected_plano_habilitados = $grupo->plano_habilitacao ? explode(';', $grupo->plano_habilitacao):[];
        }
        $this->categories = Category::all();

        $this->campos_referencia[] = [
            'id' => 'valor_caixa',
            'name' => 'Valor Caixa',
        ];

        $this->campos_referencia[] = [
            'id' => 'base_faturamento_compra',
            'name' => 'Base Faturamento Compra',
        ];

        $this->campos_referencia[] = [
            'id' => 'valor_franquia',
            'name' => 'Valor Franquia',
        ];



    }
    public function render()
    {


        return view('livewire.admin.group.criar');
    }

    public function search(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = GrupoEstoque::where('id', $this->selected_grupo_estoque)->get();

        $this->usersSearchable = GrupoEstoque::query()
            ->where('nome', 'like', "%$value%")
            ->take(5)
            ->orderBy('nome')
            ->get()
            ->merge($selectedOption);     // <-- Adds selected option
    }

    public function save()
    {
        if ($this->id !== null) {
            $grupo = Grupo::find($this->id);
            $grupo->nome = $this->nome;
            $grupo->category_id = $this->selected_category;
            $grupo->principal = $this->active;
            $grupo->descricao = $this->descricao;
            $grupo->modalidade_venda =count($this->selected_modalidade_vendas)>0 ? collect($this->selected_modalidade_vendas)->implode(';'): '';
            $grupo->plano_habilitacao =count($this->selected_plano_habilitados) > 0 ? collect($this->selected_plano_habilitados)->implode(';'): '';
            $grupo->grupo_estoque = count($this->selected_grupo_estoque) > 0 ? collect($this->selected_grupo_estoque)->implode(';') : '';
            $grupo->campo_valor = $this->campo_valor;
            $grupo->save();
            redirect()->route('admin.groups');
        } else {
            $grupo = new Grupo();
            $grupo->nome = $this->nome;
            $grupo->category_id = $this->selected_category;
            $grupo->principal = $this->active;
            $grupo->descricao = $this->descricao;
            $grupo->modalidade_venda =count($this->selected_modalidade_vendas)>0 ? collect($this->selected_modalidade_vendas)->implode(';'): '';
            $grupo->plano_habilitacao =count($this->selected_plano_habilitados) > 0 ? collect($this->selected_plano_habilitados)->implode(';'): '';
            $grupo->grupo_estoque = count($this->selected_grupo_estoque) > 0 ? collect($this->selected_grupo_estoque)->implode(';') : '';
            $grupo->campo_valor = $this->campo_valor;
            $grupo->save();
            redirect()->route('admin.groups');
        }
    }

    public function getGrupoEstoque()
    {
        $data = GrupoEstoque::query()
            ->orderBy('nome', 'asc')
            ->get();

        $grupo_estoque = [];
        foreach ($data as $grupo) {
            $grupo_estoque[] = [
                'id' => $grupo->id,
                'name' => $grupo->nome,

            ];
        }


        return $grupo_estoque;
    }

    public function getModalidadeVendas()
    {
        $data = ModalidadeVenda::query()
            ->orderBy('nome', 'asc')
            ->get();

        $modalidade_vendas = [];
        foreach ($data as $row) {
            $modalidade_vendas[] = [
                'id' => $row->id,
                'name' => $row->nome,

            ];
        }


        return $modalidade_vendas;
    }

    public function getPlanoHabilitados()
    {
        $data = PlanoHabilitacao::query()
            ->orderBy('nome', 'asc')
            ->get();

        $plano_habilitacao = [];
        foreach ($data as $row) {
            $plano_habilitacao[] = [
                'id' => $row->id,
                'name' => $row->nome,

            ];
        }


        return $plano_habilitacao;
    }
}
