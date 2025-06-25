<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class GrupoEstoqueExport implements FromQuery
{
    use Exportable;

    public function __construct($mes,$ano,$grupo_estoque)
    {
        $this->mes = $mes;
        $this->ano = $ano;
        $this->grupo_estoque = $grupo_estoque;
    }
    /**
    * @return \Illuminate\Support\Collection
    */


    public function query()
    {
        return \App\Models\Venda::query()
            ->select('id', 'numero_pv','data_pedido','filial_id','vendedor_id','vendas.descricao_comercial', 'valor_caixa')
            ->where('grupo_estoque', $this->grupo_estoque)
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->orderBy('data_pedido', 'desc');
    }
}
