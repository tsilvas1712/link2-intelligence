<?php

namespace App\Console\Commands;

use App\Jobs\TransferDatasysJob;
use App\Models\Datasys;
use App\Models\Filial;
use App\Models\Import;
use App\Models\Vendedor;
use Illuminate\Console\Command;

class TransferDataSysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->transferData();
    }


    public function getFilial($filial)
    {
        return Filial::where('filial', $filial)->first();
    }

    public function getVendedor($vendedor)
    {
        return Vendedor::where('cpf', $vendedor)->first();
    }

    public function transferData()
    {
        $data = [];
        Import::where('transfered', false)->chunk(1000, function ($imports) {
            foreach ($imports as $item) {
                $filial = $this->getFilial($item->filial);

                $vendedor = $this->getVendedor($item->cpf_vendedor);
                $venda = [
                    'area' => $item->area,
                    'regional' => $item->regional,
                    'filial_id' => $filial->id,
                    'vendedor_id' => $vendedor->id,
                    'gsm' => $item->gsm,
                    'gsm_portado' => $item->gsm_portado,
                    'contrato' => $item->contrato,
                    'numero_pv' => $item->numero_pv,
                    'data_pedido' => $item->data_pedido,
                    'tipo_pedido' => $item->tipo_pedido,
                    'nota_fiscal' => $item->nota_fiscal,
                    'cod_produto' => $item->cod_produto,
                    'descricao_comercial' => $item->descricao_comercial,
                    'descricao' => $item->descricao,
                    'grupo_estoque' => $item->grupo_estoque,
                    'sub_grupo' => $item->sub_grupo,
                    'familia' => $item->familia,
                    'fabricante' => $item->fabricante,
                    'categoria' => $item->categoria,
                    'tipo_produto'  => $item->tipo_produto,
                    'serial' => $item->serial,
                    'qtde' => $item->qtde,
                    'valor_tabela' => $item->valor_tabela,
                    'valor_plano' => $item->valor_plano,
                    'valor_caixa'   => $item->valor_caixa,
                    'descontos' => $item->descontos,
                    'juros' => $item->juros,
                    'total_item' => $item->total_item,
                    'valor_franquia' => $item->valor_franquia,
                    'desconto_compra' => $item->desconto_compra,
                    'custo_total' => $item->custo_total,
                    'cpf_cliente' => $item->cpf_cliente,
                    'nome_cliente' => $item->nome_cliente,
                    'uf_cliente'    => $item->uf_cliente,
                    'cidade_cliente' => $item->cidade_cliente,
                    'fone_cliente' => $item->fone_cliente,
                    'plano_habilitacao' => $item->plano_habilitacao,
                    'valor_pre' => $item->valor_pre,
                    'combo' => $item->combo,
                    'valor_plano_anterior' => $item->valor_plano_anterior,
                    'qtde_pontos' => $item->qtde_pontos,
                    'base_faturamento_compra' => $item->base_faturamento_compra,
                    'base_faturamento_venda' => $item->base_faturamento_venda,
                    'valor_unitario' => $item->valor_unitario,
                    'biometria' => $item->biometria,
                    'status_linha' => $item->status_linha,
                ];
                TransferDatasysJob::dispatch($venda, $item->id);
            }
        });

        Datasys::where('transfered', false)->chunk(1000, function ($datasys) {
            foreach ($datasys as $item) {
                $filial = $this->getFilial(str_replace(" ","",$item->Filial));

                $vendedor = $this->getVendedor($item->CPF_x0020_Vendedor);
                $venda = [
                    'area' => $item->Area,
                    'regional' => $item->Regional,
                    'filial_id' => $filial->id,
                    'vendedor_id' => $vendedor->id,
                    'gsm' => $item->GSM,
                    'gsm_portado' => $item->GSMPortado,
                    'contrato' => $item->Contrato,
                    'numero_pv' => $item->Numero_x0020_Pedido,
                    'data_pedido' => $item->Data_x0020_pedido,
                    'tipo_pedido' => $item->Tipo_x0020_Pedido,
                    'nota_fiscal' => $item->Nota_x0020_Fiscal,
                    'cod_produto' => $item->Cod_x0020_produto,
                    'descricao_comercial' => $item->Descr_x0020_Comercial,
                    'descricao' => $item->Descricao,
                    'grupo_estoque' => $item->Grupo_x0020_Estoque,
                    'sub_grupo' => $item->SubGrupo,
                    'familia' => $item->Familia,
                    'fabricante' => $item->Fabricante,
                    'categoria' => $item->Categoria,
                    'tipo_produto'  => $item->Tipo_x0020_Produto,
                    'serial' => $item->Serial,
                    'qtde' => $item->Qtde,
                    'valor_tabela' => $item->Valor_x0020_Tabela,
                    'valor_plano' => $item->Valor_x0020_Plano,
                    'valor_caixa'   => $item->Valor_x0020_Caixa,
                    'descontos' => $item->Descontos,
                    'juros' => $item->Juros,
                    'total_item' => $item->Total_x0020_Item,
                    'valor_franquia' => $item->ValorFranquia,
                    'desconto_compra' => $item->Descontos_x0020_Compra,
                    'custo_total' => $item->Custo_x0020_Total,
                    'cpf_cliente' => $item->CPF_x0020_Cliente,
                    'nome_cliente' => $item->Nome_x0020_Cliente,
                    'uf_cliente'    => $item->UF_x0020_Cliente,
                    'cidade_cliente' => $item->Cidade_x0020_Cliente,
                    'fone_cliente' => $item->Fone_x0020_Cliente,
                    'plano_habilitacao' => $item->Plano_x0020_Habilitacao,
                    'valor_pre' => $item->VALOR_x0020_PRE,
                    'combo' => $item->COMBO,
                    'valor_plano_anterior' => $item->Valor_x0020_Plano_x0020_Anterior,
                    'qtde_pontos' => $item->Qtde_x0020_Pontos,
                    'base_faturamento_compra' => $item->BASE_x0020_FATURAMENTO_x0020_COMPRA,
                    'base_faturamento_venda' => $item->BASE_x0020_FATURAMENTO_x0020_VENDA,
                    'valor_unitario' => $item->Valor_x0020_Unitario,
                    'biometria' => $item->Biometria,
                    //'status_linha' => $item->status_linha,
                ];
                TransferDatasysJob::dispatch($venda, $item->datasys_id);
            }
        });
    }
}
