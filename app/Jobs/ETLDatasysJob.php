<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ETLDatasysJob implements ShouldQueue
{
    use Queueable, Batchable;

    public $timeout = 600;

    protected $data;
    protected $id_mongo;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $id_mongo = null)
    {

        $this->id_mongo = $id_mongo;
        $this->data = $data;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filial = trim(str_replace(' ', '', $this->data['Filial'] ?? $this->data['filial']));
        $cpf_vendedor = trim(str_replace(' ', '', $this->data['CPF_x0020_Vendedor'] ?? $this->data['cpf_vendedor']));


        $filial_id = $this->getFilialId($filial);
        $nome_vendedor = trim($this->data['Nome_x0020_Vendedor'] ?? $this->data['nome_vendedor'] ?? '');
        $vendedor_id = $this->getVendedorId($cpf_vendedor, $nome_vendedor);

        if (!$filial_id) {
            Log::error('Número do Pedido: ' . $this->data['numero_pv'] . 'Erro ao criar filial: ' . $filial);
            return;
        }
        if (!$vendedor_id) {
            Log::error('Número do Pedido: ' . $this->data['numero_pv'] . 'Erro ao criar vendedor: ' . $cpf_vendedor);
            return;
        }


        $venda = [
            'filial_id' => $filial_id,
            'vendedor_id' => $vendedor_id,
            'gsm' => $this->data['GSM'] ?? $this->data['gsm'],
            'gsm_portado' => $this->data['GSMPortado'] ?? $this->data['gsm_portado'] ?? null,
            'contrato' => $this->data['Contrato'] ?? $this->data['contrato'] ?? null,
            'numero_pv' => $this->data['Numero_x0020_Pedido'] ?? $this->data['numero_pv'] ?? null,
            'data_pedido' => $this->data['Data_x0020_pedido'] ?? $this->data['data_pedido'] ?? null,
            'tipo_pedido' => $this->data['Tipo_x0020_Pedido'] ?? $this->data['tipo_pedido'] ?? null,
            'cod_produto' => $this->data['Cod_x0020_produto'] ?? $this->data['cod_produto'] ?? null,
            'modalidade_venda' => $this->data['Modalidade_x0020_Venda'] ?? $this->data['modalidade_venda'] ?? null,
            'descricao_comercial' => $this->data['Descr_x0020_Comercial'] ?? $this->data['descricao_comercial'] ?? null,
            'descricao' => $this->data['Descricao'] ?? $this->data['descricao'] ?? null,
            'grupo_estoque' => $this->data['Grupo_x0020_Estoque'] ?? $this->data['grupo_estoque'] ?? null,
            'sub_grupo' => $this->data['SubGrupo'] ?? $this->data['sub_grupo'] ?? null,
            'familia' => $this->data['Familia'] ?? $this->data['familia'] ?? null,
            'fabricante' => $this->data['Fabricante'] ?? $this->data['fabricante'] ?? null,
            'categoria' => $this->data['Categoria'] ?? $this->data['categoria'] ?? null,
            'tipo_produto' => $this->data['Tipo_x0020_Produto'] ?? $this->data['tipo_produto'] ?? null,
            'serial' => $this->data['Serial'] ?? $this->data['serial'] ?? null,
            'qtde' => $this->data['Qtde'] ?? $this->data['qtde'] ?? null,
            'valor_tabela' => $this->data['Valor_x0020_Tabela'] ?? $this->data['valor_tabela'] ?? null,
            'valor_plano' => $this->data['Valor_x0020_Plano'] ?? $this->data['valor_plano'] ?? null,
            'valor_caixa' => $this->data['Valor_x0020_Caixa'] ?? $this->data['valor_caixa'] ?? null,
            'descontos' => $this->data['Descontos'] ?? $this->data['descontos'] ?? null,
            'juros' => $this->data['Juros'] ?? $this->data['juros'] ?? null,
            'total_item' => $this->data['Total_x0020_Item'] ?? $this->data['total_item'] ?? null,
            'valor_franquia' => $this->data['ValorFranquia'] ?? $this->data['valor_franquia'] ?? null,
            'desconto_compra' => $this->data['Descontos_x0020_Compra'] ?? $this->data['desconto_compra'] ?? null,
            'custo_total' => $this->data['Custo_x0020_Total'] ?? $this->data['custo_total'] ?? null,
            'cpf_cliente' => $this->data['CPF_x0020_Cliente'] ?? $this->data['cpf_cliente'] ?? null,
            'nome_cliente' => $this->data['Nome_x0020_Cliente'] ?? $this->data['nome_cliente'] ?? null,
            'uf_cliente' => $this->data['UF_x0020_Cliente'] ?? $this->data['uf_cliente'] ?? null,
            'cidade_cliente' => $this->data['Cidade_x0020_Cliente'] ?? $this->data['cidade_cliente'] ?? null,
            'fone_cliente' => $this->data['Fone_x0020_Cliente'] ?? $this->data['fone_cliente'] ?? null,
            'plano_habilitacao' => $this->data['Plano_x0020_Habilitacao'] ?? null,
            'valor_pre' => $this->data['VALOR_x0020_PRE'] ?? $this->data['valor_pre'] ?? null,
            'combo' => $this->data['COMBO'] ?? $this->data['combo'] ?? null,
            'valor_plano_anterior' => $this->data['Valor_x0020_Plano_x0020_Anterior'] ?? $this->data['valor_plano_anterior'] ?? null,
            'qtde_pontos' => $this->data['Qtde_x0020_Pontos'] ?? $this->data['qtde_pontos'] ?? null,
            'base_faturamento_compra' => $this->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? $this->data['base_faturamento_compra'] ?? null,
            'base_faturamento_venda' => $this->data['BASE_x0020_FATURAMENTO_x0020_VENDA'] ?? $this->data['base_faturamento_venda'] ?? null,
            'valor_unitario' => $this->data['Valor_x0020_Unitario'] ?? $this->data['valor_unitario'] ?? null,

            //'status_linha' => $item->status_linha,
        ];

        try {
            \App\Models\Venda::query()
                ->create($venda);

            try {
                \App\Models\SyncMongo::query()
                    ->where('id', $this->id_mongo)
                    ->update([
                        'error_migrate' => null,
                        'migrated' => true,
                        'updated_at' => now(),
                    ]);
            } catch (\Exception $e) {
                Log::error('Número do Pedido: ' . $this->data['numero_pv'] . 'Erro ao criar sync mongo: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            \App\Models\SyncMongo::query()
                ->where('id', $this->id_mongo)
                ->update([
                    'error_migrate' => json_encode($e->getMessage()),
                    'updated_at' => now(),
                ]);

            Log::error('ID Mongo ' . $this->id_mongo . ' Erro ao criar venda: ' . json_encode($e->getMessage()));
        }
    }

    public function getFilialId($filial)
    {
        $response = \App\Models\Filial::query()
            ->where('filial', $filial)
            ->first();

        if ($response) {
            return $response->id;
        }


        $filial_new = \App\Models\Filial::query()
            ->create([
                'filial' => $filial,

            ]);

        return $filial_new->id;
    }

    public function getVendedorId($cpf_vendedor, $nome_vendedor)
    {
        $vendedor = \App\Models\Vendedor::query()
            ->where('cpf', $cpf_vendedor)
            ->first();

        if ($vendedor) {
            return $vendedor->id;
        }

        $vendedor_new = \App\Models\Vendedor::query()
            ->create([
                'cpf' => $cpf_vendedor,
                'nome' => $nome_vendedor,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return $vendedor_new->id;
    }
}
