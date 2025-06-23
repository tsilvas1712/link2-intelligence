<?php

namespace App\Livewire\Admin\Datasys;

use App\Models\SyncMongo;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedTab = 'sync-tab';
    public function render()
    {
        return view('livewire.admin.datasys.dashboard');
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
