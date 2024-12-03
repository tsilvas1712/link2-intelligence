<?php

namespace App\Services;

use App\Models\Filial;
use App\Models\Venda;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ImagemTelecomService
{
    public $vendas;
    public $mes;
    public $ano;
    /**
     * Create a new class instance.
     */
    public function __construct(Venda $venda)
    {
        $this->vendas = $venda;
        $this->mes = '05';//Carbon::now()->format('m');
        $this->ano = '2024';//Carbon::now()->format('Y');

    }

    public function tendencia($total)
    {

        $totalDias = Cache::remember('totalDias', 60, function () {
            return $this->vendas->query()
                ->select('data_pedido')
                ->whereMonth('data_pedido', '=', '05')
                ->groupBy('data_pedido')
                ->get();
        });

        $media = floatVal($total) / count($totalDias);

        return $media * count($totalDias);
    }

    public function meta()
    {
        return 'Faturamento';
    }

    public function rankingVendedores()
    {
        return $this->vendas->query()
        ->select('vendedor_id', DB::raw('sum(valor_caixa) as Total'))
        ->whereMonth('data_pedido', '=', '05')
        ->groupBy('vendedor_id')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();
    }

    public function rankingFiliais()
    {
        return $this->vendas->query()
        ->select('filial_id', DB::raw('sum(valor_caixa) as Total'))
        ->where('tipo_pedido', 'Venda')
        ->whereMonth('data_pedido', '=', '05')
        ->groupBy('filial_id')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();
    }

    public function rankingFabricantes()
    {
        return $this->vendas->query()
        ->select('fabricante', DB::raw('sum(valor_caixa) as Total'))
        ->where('tipo_pedido', 'Venda')
        ->where('grupo_estoque', 'APARELHO')
        ->where('fabricante','<>','')
        ->whereMonth('data_pedido', '=', '05')
        ->groupBy('fabricante')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();
    }

    public function faturamento($mes,$ano): float
    {
        $total =  $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->whereMonth('data_pedido', '=', $mes)
                ->whereYear('data_pedido', '=', $ano)
                ->sum('valor_caixa');


        return floatVal($total);

    }




    public function faturamentoFilial($filial_id)
    {
        $total = Cache::remember('totalFilial', 60, function () use ($filial_id) {
            return $this->vendas->query()
                ->where('filial_id', $filial_id)
                ->whereMonth('data_pedido', '=', '05')
                ->sum('valor_caixa');
        });

        return floatVal($total);
    }

    public function faturamentoVendedor($vendedor_id)
    {
        $total = Cache::remember('totalVendedor', 60, function () use ($vendedor_id) {
            return $this->vendas->query()
                ->where('vendedor_id', $vendedor_id)
                ->whereMonth('data_pedido', '=', '05')
                ->sum('valor_caixa');
        });

        return floatVal($total);
    }

    public function tendenciaFilial($filial_id)
    {
        $total = Cache::remember('totalFilial', 60, function () use ($filial_id) {
            return $this->vendas->query()
                ->where('filial_id', $filial_id)
                ->whereMonth('data_pedido', '=', '05')
                ->sum('valor_caixa');
        });

        $totalDias = Cache::remember('totalDiasFilial', 60, function () use ($filial_id) {
            return $this->vendas->query()
                ->select('data_pedido')
                ->where('filial_id', $filial_id)
                ->whereMonth('data_pedido', '=', '05')
                ->groupBy('data_pedido')
                ->get();
        });

        $media = floatval($total) / count($totalDias);


        return $media * count($totalDias);
    }

    public function metaFilial($filial_id): float
    {
        return 300000;
    }

    public function topVendedores()
    {
        $topVendedores =  Cache::remember('topVendedores', 60, function () {
            return $this->vendas->query()
                ->select('vendedor_id', DB::raw('sum(valor_caixa) as Total'))
                ->whereMonth('data_pedido', '=', '05')
                ->groupBy('vendedor_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        });

        return $topVendedores;
    }

    public function getNomeVendedor($vendedor_id)
    {
        $vendedor = Vendedor::query()
            ->select('nome')
            ->where('id', $vendedor_id)
            ->first();

        return $vendedor->nome;
    }

    public function getNomeFilial($filial_id)
    {
        $filial = Filial::query()
            ->select('filial')
            ->where('id', $filial_id)
            ->first();

        return $filial->filial;
    }

    public function tendenciaVendedor($vendedor_id)
    {
        $total = Cache::remember('totalVendedor', 60, function () use ($vendedor_id) {
            return $this->vendas->query()
                ->where('vendedor_id', $vendedor_id)
                ->whereMonth('data_pedido', '=', '05')
                ->sum('valor_caixa');
        });

        $totalDias = Cache::remember('totalDiasVendedor', 60, function () use ($vendedor_id) {
            return $this->vendas->query()
                ->select('data_pedido')
                ->where('vendedor_id', $vendedor_id)
                ->whereMonth('data_pedido', '=', '05')
                ->groupBy('data_pedido')
                ->get();
        });

        try {
            $media = floatval($total) / count($totalDias);
        } catch (\DivisionByZeroError $e) {
            $media = 0;
        }

        return $media * count($totalDias);
    }

    public function diasTrabalhados()
    {
        return 22;
    }

    public function vendasDiarias()
    {
        return $this->vendas->query()
            ->select('data_pedido', DB::raw("sum(valor_caixa) as Total"))
            ->whereMonth('data_pedido', '=', '05')
            ->groupBy('data_pedido')
            ->get();
    }

    public function diasFaltantes()
    {
        return 5;
    }

    public function totalChip(){
        $total = Cache::remember('totalChip', 60, function () {
            return $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->where('grupo_estoque', 'CHIP')
                ->whereMonth('data_pedido', '=', $this->mes)
                ->whereYear('data_pedido', '=', $this->ano)
                ->sum('valor_caixa');
        });

        return floatVal($total);

    }

    public function totalRecarga(){
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereIn('grupo_estoque', ['RECARGA ELETRONICA', 'RECARGA GWCEL'])
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_caixa');

    }

    public function totalFranquia(){
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_franquia');

    }

    public function totalAcessorios(){
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_caixa');

    }

    public function totalAparelhos(){
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->where('grupo_estoque', 'APARELHO')
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('base_faturamento_compra');

    }

    public function totalFaturamento(){
        return $this->totalAcessorios() + $this->totalAparelhos() + $this->totalChip() + $this->totalFranquia();
    }
}
