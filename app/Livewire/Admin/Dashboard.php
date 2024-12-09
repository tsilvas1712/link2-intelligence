<?php

namespace App\Livewire\Admin;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Venda;
use App\Models\Vendedor;
use Livewire\Component;

class Dashboard extends Component
{
    public $usuarios;
    public $filiais;
    public $vendedores;
    public $planos;
    public $meses;
    public $vendas;

    public function mount()
    {
        $this->usuarios = User::count();
        $this->filiais = Filial::count();
        $this->vendedores = Vendedor::count();
        $this->planos = Grupo::count();
        $this->vendas = Venda::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
