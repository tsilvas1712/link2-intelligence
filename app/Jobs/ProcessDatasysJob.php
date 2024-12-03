<?php

namespace App\Jobs;

use App\Models\Datasys;
use App\Models\Import;
use Carbon\Carbon;
use Exception;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDatasysJob implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $datasys = new Datasys();

        foreach ($this->data as $key => $value) {
            $datasys->$key = is_array($value) ? NULL : trim($value);
        }

        try {

            $datasys->save();
        } catch (Exception $e) {
            Log::error('Erro ao importar: ' . $this->data['Numero_x0020_Pedido'] . ' - ' . $e->getMessage());
            Log::debug($this->data);
        }
    }




    public function dePara($data)
    {

        $dataImport = [
            'area' => $data['Area'],
            'tenant_id' => $data['tenant_id'],
            'regional' => $data['Regional'] ?? null,
            'filial' => str_replace(" ", "", $data['Filial']),
            'gsm' => is_array($data['GSM']) ? ' ' : $data['GSM'],
            //'gsm_portado' => ($data['GSMPortado']) ? (is_array($data['GSMPortado']) ? '' : $data['GSMPortado']) : null,
            //'contrato' => is_array($data['Contrato']) ? ' ' : $data['Contrato'],
            'numero_pv' => $data['Numero_x0020_Pedido'],
            'data_pedido' => Carbon::parse(str_replace('/', '-', $data['Data_x0020_pedido']))->format('Y-m-d'),
            'tipo_pedido' => is_array($data['Tipo_x0020_Pedido']) ? ' ' : $data['Tipo_x0020_Pedido'],
            'pedido_anterior' => is_array($data['PedidoAnterior']) ? ' ' : $data['PedidoAnterior'],
            'numero_ordem_siebel' => $data['Número Ordem Siebel'] ?? null,
            'cupom_fiscal' => $data['Cupom Fiscal'] ?? null,
            'nota_fiscal' => $data['Nota Fiscal'] ?? null,
            'cod_produto' => $data['Cod_x0020_produto'],
            'descricao_comercial' => $data['Descr_x0020_Comercial'] ?? null,
            'descricao' => $data['Descricao'] ?? null,
            'grupo_estoque' => $data['Grupo_x0020_Estoque'],
            'sub_grupo' => $data['Sub Grupo'] ?? null,
            'familia' => $data['Família'] ?? null,
            'fabricante' => $data['Fabricante'] ?? null,
            'categoria' => $data['Categoria'] ?? null,
            'tipo_produto' => $data['Tipo_x0020_Produto'],
            'serial' => $data['Serial'] ?? null,
            'qtde' => $data['Qtde'],
            'valor_tabela' => $this->convertToDecimal($data['Valor_x0020_Tabela']),
            'valor_plano' => $this->convertToDecimal($data['Valor_x0020_Plano']),
            'valor_caixa' => $this->convertToDecimal($data['Valor Caixa'] ?? 0),
            'descontos' => $this->convertToDecimal($data['Desconto_x0020_Plano']),
            'juros' => $this->convertToDecimal($data['Juros']),
            'total_item' => $this->convertToDecimal($data['Total_x0020_Item']),
            //'valor_franquia' => $this->convertToDecimal($data['ValorFranquia']),
            'voucher_plano' => $this->convertToDecimal($data['voucher']),
            'voucher_loja' => $this->convertToDecimal($data['VoucherLoja']),
            'numero_voucher_loja' => $data['Número Voucher Loja'] ?? null,
            //'custo_inicial' => $this->convertToDecimal($data['Custo Inicial']),
            //'impostos' => $this->convertToDecimal($data['Impostos']),
            //'desconto_compra' => $this->convertToDecimal($data['Desconto Compra']),
            //'custo_total' => $this->convertToDecimal($data['Custo Total']),
            //'fornecedor' => $data['Fornecedor'],
            //'dt_compra' => Carbon::parse(str_replace('/', '-', $data['DT Compra']))->format('Y-m-d'),
            //'nf_compra' => $data['NF Compra'],
            // 'financeira' => $data['Financeira'],
            //'forma_pgto_1' => $data['Forma Pgto 1'],
            //'valor_pgto_1' => $this->convertToDecimal($data['Valor Pgto 1']),
            //'forma_pgto_2' => $data['Forma Pgto 2'],
            //'valor_pgto_2' => $this->convertToDecimal($data['Valor Pgto 2']),
            //'forma_pgto_3' => $data['Forma Pgto 3'],
            //'valor_pgto_3' => $this->convertToDecimal($data['Valor Pgto 3']),
            //'forma_pgto_4' => $data['Forma Pgto 4'],
            //'valor_pgto_4' => $this->convertToDecimal($data['Valor Pgto 4']),
            //'forma_pgto_5' => $data['Forma Pgto 5'],
            //'valor_pgto_5' => $this->convertToDecimal($data['Valor Pgto 5']),
            //'forma_pgto_6' => $data['Forma Pgto 6'],
            //'valor_pgto_6' => $this->convertToDecimal($data['Valor Pgto 6']),
            //'forma_pgto_7' => $data['Forma Pgto 7'],
            //'valor_pgto_7' => $this->convertToDecimal($data['Valor Pgto 7']),
            //'forma_pgto_8' => $data['Forma Pgto 8'],
            //'valor_pgto_8' => $this->convertToDecimal($data['Valor Pgto 8']),
            //'forma_pgto_9' => $data['Forma Pgto 9'],
            //'valor_pgto_9' => $this->convertToDecimal($data['Valor Pgto 9']),
            //'forma_pgto_10' => $data['Forma Pgto 10'],
            //'valor_pgto_10' => $this->convertToDecimal($data['Valor Pgto 10']),
            'cpf_vendedor' => str_replace("'", "", $data['CPF_x0020_Vendedor']),
            'nome_vendedor' => $data['Nome_x0020_Vendedor'],
            'cpf_cliente' => str_replace("'", "", $data['CPF_x0020_Cliente']),
            'nome_cliente' => $data['Nome_x0020_Cliente'],
            'uf_cliente' => $data['UF_x0020_Cliente'],
            'cidade_cliente' => $data['Cidade_x0020_Cliente'],
            //'email_cliente' => $data['Email Cliente'],
            'fone_cliente' => $data['Fone_x0020_Cliente'],
            'plano_habilitacao' => $data['Plano_x0020_Habilitacao'] ?? null,
            'claro_sim' => $data['CLARO_x0020_SIM'],
            'tipo_habilitacao' => $data['Tipo_x0020_Habilitacao'],
            //'grade_precos' => $data['Grade Preços'],
            //'tipo_pacote' => is_array($data['Tipo_x0020_Pacote']) ? ' ' : $data['Tipo_x0020_Pacote'],
            'modalidade_venda' => $data['Modalidade_x0020_Venda'],
            //'operadora_origem' => $data['Operadora Origem'],
            'desconto_plano' => $this->convertToDecimal($data['Desconto_x0020_Plano']),
            'mp_do_bem' => $data['MP_x0020_do_x0020_BEM'],
            //'valor_pre' => $this->convertToDecimal($data['Valor Pre']),
            //'combo' => $data['Combo'],
            'logradouro' => $data['Logradouro'],
            'numero' => $data['numero'],
            'complemento' => is_array($data['Complemento']) ? ' ' : $data['Complemento'],
            'bairro' => $data['bairro'],
            'cidade' => $data['Cidade'],
            'cep' => $data['cep'],
            'uf' => $data['uf'],
            //'tv' => $data['TV'],
            //'fixo' => $data['Fixo'],
            // 'movel' => $data['Móvel'],
            //'internet' => $data['Internet'],
            'item' => $data['Item'],
            //'mpb' => $data['MPB'],
            'valor_plano_anterior' => $this->convertToDecimal($data['Valor_x0020_Plano_x0020_Anterior']),
            //'qtde_pontos' => $data['Qtde Pontos'],
            'score_cliente' => is_array($data['Score_x0020_Cliente']) ? ' ' : $data['Score_x0020_Cliente'],
            //'plano_antigo' => $data['Plano_x0020_Antigo'] ?? null,
            //'dia_vencimento' => $data['Dia Vencimento'],
            //'status_tim_capture' => $data['Status TIM CAPTURE'],
            //'base_faturamento_compra' => $this->convertToDecimal($data['Base Faturamento Compra']),
            //'base_faturamento_venda' => $this->convertToDecimal($data['Base Faturamento Venda']),
            'fidelizacao' => $data['Fidelização'] ?? null,
            //'fidelizacao_plano' => $data['Fidelizacao_x0020_Plano'],
            'plug_in' => $data['Plugin'],
            //'valor_unitario' => $this->convertToDecimal($data['Valor Unitario']),
            'biometria' => $data['Biometria'],
            //'ean' => $data['EAN'],
            //'id_tfp' => $data['ID TFP'],
            //'status_linha' => $data['Status Linha'],
        ];


        return $dataImport;
    }

    public function convertToDecimal($value)
    {
        $valor = str_replace("R$", "",  $value);
        $valor = str_replace(".", "",  $valor);
        $valor = str_replace(",", ".",  $valor);

        if ($valor == '') {
            return 0;
        }
        return $valor;
    }

    
}
