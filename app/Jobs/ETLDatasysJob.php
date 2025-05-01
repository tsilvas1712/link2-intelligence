<?php

namespace App\Jobs;

use App\Models\Plano;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ETLDatasysJob implements ShouldQueue
{
    use Queueable;

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
        $filial = trim(str_replace(' ', '', $this->data['Filial']));
        $cpf_vendedor = trim(str_replace(' ', '', $this->data['CPF_x0020_Vendedor']));





        $filial_id = $this->getFilialId($filial);
        $nome_vendedor = trim($this->data['Nome_x0020_Vendedor']);
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
            'gsm' => $this->data['GSM'],
            'gsm_portado' => $this->data['GSMPortado'] ?? null,
            'contrato' => $this->data['Contrato'] ?? null,
            'numero_pv' => $this->data['Numero_x0020_Pedido'] ?? null,
            'data_pedido' => $this->data['Data_x0020_pedido'] ?? null,
            'tipo_pedido' => $this->data['Tipo_x0020_Pedido'] ?? null,
            'cod_produto' => $this->data['Cod_x0020_produto'] ?? null,
            'modalidade_venda' => $this->data['Modalidade_x0020_Venda'] ?? null,
            'descricao_comercial' => $this->data['Descr_x0020_Comercial'] ?? null,
            'descricao' => $this->data['Descricao'] ?? null,
            'grupo_estoque' => $this->data['Grupo_x0020_Estoque'] ?? null,
            'sub_grupo' => $this->data['SubGrupo'] ?? null ?? null,
            'familia' => $this->data['Familia'] ?? null ?? null,
            'fabricante' => $this->data['Fabricante'] ?? null ?? null,
            'categoria' => $this->data['Categoria'] ?? null ?? null,
            'tipo_produto'  => $this->data['Tipo_x0020_Produto'] ?? null,
            'serial' => $this->data['Serial'] ?? null,
            'qtde' => $this->data['Qtde'] ?? null,
            'valor_tabela' => $this->data['Valor_x0020_Tabela'] ?? null,
            'valor_plano' => $this->data['Valor_x0020_Plano'] ?? null,
            'valor_caixa'   => $this->data['Valor_x0020_Caixa'] ?? null ?? null,
            'descontos' => $this->data['Descontos'] ?? null,
            'juros' => $this->data['Juros'] ?? null,
            'total_item' => $this->data['Total_x0020_Item'] ?? null,
            'valor_franquia' => $this->data['ValorFranquia'] ?? null,
            'desconto_compra' => $this->data['Descontos_x0020_Compra'] ?? null,
            'custo_total' => $this->data['Custo_x0020_Total'] ?? null,
            'cpf_cliente' => $this->data['CPF_x0020_Cliente'] ?? null,
            'nome_cliente' => $this->data['Nome_x0020_Cliente'] ?? null,
            'uf_cliente'    => $this->data['UF_x0020_Cliente'] ?? null,
            'cidade_cliente' => $this->data['Cidade_x0020_Cliente'] ?? null,
            'fone_cliente' => $this->data['Fone_x0020_Cliente'] ?? null,
            'plano_habilitacao' => $this->data['Plano_x0020_Habilitacao'] ?? null,
            'valor_pre' => $this->data['VALOR_x0020_PRE'] ?? null,
            'combo' => $this->data['COMBO'] ?? null,
            'valor_plano_anterior' => $this->data['Valor_x0020_Plano_x0020_Anterior'] ?? null,
            'qtde_pontos' => $this->data['Qtde_x0020_Pontos'] ?? null,
            'base_faturamento_compra' => $this->data['BASE_x0020_FATURAMENTO_x0020_COMPRA'] ?? null,
            'base_faturamento_venda' => $this->data['BASE_x0020_FATURAMENTO_x0020_VENDA'] ?? null,
            'valor_unitario' => $this->data['Valor_x0020_Unitario'] ?? null,

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
