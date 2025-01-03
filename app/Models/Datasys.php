<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datasys extends Model
{
    use HasFactory;

    protected $table = 'datasys';

    protected $fillble = [
        'datasys_id',
        'id',
        'GSM',
        'GSMPortado',
        'Contrato',
        'Area',
        'CNPJ_x0020_Filial',
        'Regional',
        'Filial',
        'Numero_x0020_Pedido',
        'Data_x0020_pedido',
        'Tipo_x0020_Pedido',
        'Cupom_x0020_Fiscal',
        'Nota_x0020_Fiscal',
        'Cod_x0020_produto',
        'Descr_x0020_Comercial',
        'Descricao',
        'NCM',
        'Grupo_x0020_Estoque',
        'SubGrupo',
        'Familia',
        'Fabricante',
        'Categoria',
        'Tipo_x0020_Produto',
        'Serial',
        'Qtde',
        'Valor_x0020_Tabela',
        'Valor_x0020_Plano',
        'Valor_x0020_Caixa',
        'Descontos',
        'Juros',
        'Total_x0020_Item',
        'voucher',
        'Custo_x0020_Inicial',
        'Impostos',
        'Custo_x0020_Total',
        'Fornecedor',
        'DT_x0020_Compra',
        'NF_x0020_Compra',
        'Financeira',
        'Forma_x0020_Pgto_x0020_1',
        'Valor_x0020_Pgto_x0020_1',
        'Forma_x0020_Pgto_x0020_2',
        'Valor_x0020_Pgto_x0020_2',
        'Forma_x0020_Pgto_x0020_3',
        'Valor_x0020_Pgto_x0020_3',
        'Forma_x0020_Pgto_x0020_4',
        'Valor_x0020_Pgto_x0020_4',
        'Forma_x0020_Pgto_x0020_5',
        'Valor_x0020_Pgto_x0020_5',
        'Forma_x0020_Pgto_x0020_6',
        'Valor_x0020_Pgto_x0020_6',
        'Forma_x0020_Pgto_x0020_7',
        'Valor_x0020_Pgto_x0020_7',
        'Forma_x0020_Pgto_x0020_8',
        'Valor_x0020_Pgto_x0020_8',
        'Forma_x0020_Pgto_x0020_9',
        'Valor_x0020_Pgto_x0020_9',
        'Forma_x0020_Pgto_x0020_10',
        'Valor_x0020_Pgto_x0020_10',
        'CPF_x0020_Vendedor',
        'Nome_x0020_Vendedor',
        'CPF_x0020_Cliente',
        'Nome_x0020_Cliente',
        'UF_x0020_Cliente',
        'Cidade_x0020_Cliente',
        'Email_x0020_Cliente',
        'Fone_x0020_Cliente',
        'Plano_x0020_Habilitacao',
        'CLARO_x0020_SIM',
        'Tipo_x0020_Habilitacao',
        'Grade_x0020_Precos',
        'Tipo_x0020_Pacote',
        'Modalidade_x0020_Venda',
        'Operadora',
        'Desconto_x0020_Plano',
        'MP_x0020_do_x0020_BEM',
        'VALOR_x0020_PRE',
        'COMBO',
        'Logradouro',
        'numero',
        'Complemento',
        'bairro',
        'Cidade',
        'cep',
        'uf',
        'TV',
        'FIXO',
        'MOVEL',
        'INTERNET',
        'Item',
        'MPB',
        'Protocolo_x0020_Claro_x0020_Clube',
        'Valor_x0020_Plano_x0020_Anterior',
        'Qtde_x0020_Pontos',
        'Score_x0020_Cliente',
        'Plano_x0020_Antigo',
        'Observacao',
        'Numero_x0020_Voucher',
        'NumeroOrdemSiebel',
        'PedidoAnterior',
        'Descontos_x0020_Compra',
        'Numero_x0020_Voucher',
        'BASE_x0020_FATURAMENTO_x0020_COMPRA',
        'BASE_x0020_FATURAMENTO_x0020_VENDA',
        'Fidelizacao',
        'VoucherLoja',
        'ValorFranquia',
        'Plugin',
        'Fidelizacao_x0020_Plano',
        'Valor_x0020_Unitario',
        'Biometria',
        'TipoTHAB',
        'EAN',
        'RecargaGWCELL',
        'transfered'
    ];
}
