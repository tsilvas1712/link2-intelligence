<?php

namespace App\Livewire\Admin;

use App\Models\Filial;
use App\Models\Grupo;
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

    public function mount()
    {
        $this->usuarios = User::count();
        $this->filiais = Filial::count();
        $this->vendedores = Vendedor::count();
        $this->planos = Grupo::count();
        $this->vendas = Venda::count();
        $this->notClassificate();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }

    #[Computed]
    public function notClassificate(): LengthAwarePaginator
    {
        $filiais = Filial::select('id')->get();
        $aFiliais = [];
        $grupo_estoque = ['APARELHO','CHIP','ACESSORIOS', 'ACESSORIOS TIM','RECARGA ELETRONICA', 'RECARGA GWCEL'];
        foreach($filiais as $filial){
            $aFiliais[]=$filial->id;
        }

        $vendas = Venda::query()
        ->whereNotIn('grupo_estoque',$grupo_estoque)
        ->where('tipo_pedido','Venda')
        ->paginate();

        return $vendas;

    }

    public function headers(){
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'filial_id', 'label' => 'Filial'],
            ['key' => 'vendedor_id', 'label' => 'Vendedor'],
            ['key' => 'gsm', 'label' => 'GSM'],
            ['key' => 'gsm_portado', 'label' => 'GSM Portado'],
            ['key' => 'tipo_pedido', 'label' => 'Tipo Pedido'],
            ['key' => 'descricao', 'label' => 'Descrição'],
            ['key' => 'grupo_estoque', 'label' => 'Grupo de Estoque'],
            ['key' => 'plano_habilitacao', 'label' => 'Plano Habilitação'],
            ['key' => 'valor_caixa', 'label' => 'Valor Caixa'],
            ['key' => 'valor_franquia', 'label' => 'Valor Franquia'],
            ['key' => 'base_faturamento_compra', 'label' => 'Base Faturamento Compra'],
            ['key' => 'modalidade_venda', 'label' => 'Modalidade de Venda'],
            ['key' => 'status_linha', 'label' => 'Status Linha'],
        ];
    }
}
