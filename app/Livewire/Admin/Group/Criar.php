<?php

namespace App\Livewire\Admin\Group;

use App\Models\Grupo;
use Livewire\Component;

class Criar extends Component
{
    public $nome;
    public $descricao;
    public $grupo_estoque;
    public $campo_valor;
    public $modalidades_vendas;
    public $planos_habilitados;

    public $id;

    public function mount($id = null)
    {
        $this->id = null;
        if ($id !== null) {
            $this->id = $id;
            $grupo = Grupo::find($id);
            $this->nome = $grupo->nome;
            $this->descricao = $grupo->descricao;
            $this->grupo_estoque = $grupo->grupo_estoque;
            $this->campo_valor = $grupo->campo_valor;
            $this->modalidades_vendas = $grupo->modalidade_venda;
            $this->planos_habilitados = $grupo->plano_habilitacao;
        }
    }
    public function render()
    {
        return view('livewire.admin.group.criar');
    }

    public function save()
    {
        if ($this->id !== null) {
            $grupo = Grupo::find($this->id);
            $grupo->nome = $this->nome;
            $grupo->descricao = $this->descricao;
            $grupo->modalidade_venda = $this->modalidades_vendas;
            $grupo->plano_habilitacao = $this->planos_habilitados;
            $grupo->grupo_estoque = $this->grupo_estoque;
            $grupo->campo_valor = $this->campo_valor;
            $grupo->save();
            redirect()->route('admin.groups');
        } else {
            $grupo = new Grupo();
            $grupo->nome = $this->nome;
            $grupo->descricao = $this->descricao;
            $grupo->modalidade_venda = $this->modalidades_vendas;
            $grupo->plano_habilitacao = $this->planos_habilitados;
            $grupo->grupo_estoque = $this->grupo_estoque;
            $grupo->campo_valor = $this->campo_valor;
            $grupo->save();
            redirect()->route('admin.groups');
        }
    }
}
