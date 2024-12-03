<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'area',
        'regional',
        'filial_id',
        'vendedor_id',
        'filial',
        'gsm',
        'gsm_portado',
        'contrato',
        'numero_pv',
        'data_pedido',
        'tipo_pedido',
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
        'desconto_compra',
        'custo_total',
        'cpf_cliente',
        'nome_cliente',
        'uf_cliente',
        'cidade_cliente',
        'fone_cliente',
        'plano_habilitacao',
        'valor_pre',
        'combo',
        'valor_plano_anterior',
        'qtde_pontos',
        'base_faturamento_compra',
        'base_faturamento_venda',
        'valor_unitario',
        'biometria',
        'status_linha',
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
