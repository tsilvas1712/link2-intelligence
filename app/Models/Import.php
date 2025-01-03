<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    //
    protected $fillable = [
        'area',
        'regional',
        'filial',
        'gsm',
        'gsm_portado',
        'contrato',
        'numero_pv',
        'data_pedido',
        'tipo_pedido',
        'pedido_anterior',
        'numero_ordem_siebel',
        'cupom_fiscal',
        'nota_fiscal',
        'cod_produto',
        'descricao_comercial',
        'descricao',
        'grupo_estoque',
        'sub_grupo',
        'familia',
        'fabricante',
        'categoria',
        'tipo_produto',
        'serial',
        'qtde',
        'valor_tabela',
        'valor_plano',
        'valor_caixa',
        'descontos',
        'juros',
        'total_item',
        'valor_franquia',
        'voucher_plano',
        'voucher_loja',
        'numero_voucher_loja',
        'custo_inicial',
        'impostos',
        'desconto_compra',
        'custo_total',
        'fornecedor',
        'dt_compra',
        'nf_compra',
        'financeira',
        'forma_pgto_1',
        'valor_pgto_1',
        'forma_pgto_2',
        'valor_pgto_2',
        'forma_pgto_3',
        'valor_pgto_3',
        'forma_pgto_4',
        'valor_pgto_4',
        'forma_pgto_5',
        'valor_pgto_5',
        'forma_pgto_6',
        'valor_pgto_6',
        'forma_pgto_7',
        'valor_pgto_7',
        'forma_pgto_8',
        'valor_pgto_8',
        'forma_pgto_9',
        'valor_pgto_9',
        'forma_pgto_10',
        'valor_pgto_10',
        'cpf_vendedor',
        'nome_vendedor',
        'cpf_cliente',
        'nome_cliente',
        'uf_cliente',
        'cidade_cliente',
        'email_cliente',
        'fone_cliente',
        'plano_habilitacao',
        'claro_sim',
        'tipo_habilitacao',
        'grade_precos',
        'tipo_pacote',
        'modalidade_venda',
        'operadora_origem',
        'desconto_plano',
        'mp_do_bem',
        'valor_pre',
        'combo',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'cep',
        'uf',
        'tv',
        'fixo',
        'movel',
        'internet',
        'item',
        'mpb',
        'valor_plano_anterior',
        'qtde_pontos',
        'score_cliente',
        'plano_antigo',
        'dia_vencimento',
        'status_tim_capture',
        'base_faturamento_compra',
        'base_faturamento_venda',
        'fidelizacao',
        'fidelizacao_plano',
        'plug_in',
        'valor_unitario',
        'biometria',
        'ean',
        'id_tfp',
        'status_linha',
    ];
}
