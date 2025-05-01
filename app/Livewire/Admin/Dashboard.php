<?php

namespace App\Livewire\Admin;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\SyncMongo;
use App\Models\User;
use App\Models\Venda;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

use function MongoDB\object;

class Dashboard extends Component
{
    use WithPagination;
    public $usuarios;
    public $filiais;
    public $vendedores;
    public $planos;
    public $meses;
    public $vendas;
    public $selectedTab = 'sync-tab';

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
        $grupo_estoque = ['APARELHO', 'CHIP', 'ACESSORIOS', 'ACESSORIOS TIM', 'RECARGA ELETRONICA', 'RECARGA GWCEL'];
        foreach ($filiais as $filial) {
            $aFiliais[] = $filial->id;
        }

        $vendas = Venda::query()
            ->whereNotIn('grupo_estoque', $grupo_estoque)
            ->whereIn('tipo_pedido', ['Venda', 'VENDA'])
            ->paginate();

        return $vendas;
    }
    public function headers()
    {
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

    #[Computed]
    public function dataMongo(): LengthAwarePaginator
    {
        $mongoDB = SyncMongo::query()
            ->where('migrated', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $mongoDB;
    }

    public function headersMongo()
    {
        return [
            ['key' => 'filial', 'label' => 'Filial'],
            ['key' => 'data', 'label' => 'Número do Pedido'],
            ['key' => 'pedido', 'label' => 'Data Pedido'],
            ['key' => 'migrated', 'label' => 'Dado Migrado'],
            ['key' => 'grupo_estoque', 'label' => 'Grupo de Estoque'],
        ];
    }

    #[Computed]
    public function errorMongo(): LengthAwarePaginator
    {
        $mongoDB = SyncMongo::query()
            ->where('migrated', false)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return $mongoDB;
    }

    public function headersErrors()
    {
        return [
            ['key' => 'filial', 'label' => 'Filial'],
            ['key' => 'data', 'label' => 'Número do Pedido'],
            ['key' => 'pedido', 'label' => 'Data Pedido'],
            ['key' => 'migrated', 'label' => 'Dado Migrado'],
            ['key' => 'grupo_estoque', 'label' => 'Grupo de Estoque'],
            ['key' => 'error_migrated', 'label' => 'Erro de Migração'],

        ];
    }
}
