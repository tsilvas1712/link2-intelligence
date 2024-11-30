<?php

namespace App\Console\Commands;

use App\Jobs\TransferDatasysJob;
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
    }
}
