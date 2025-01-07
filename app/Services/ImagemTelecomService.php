<?php

namespace App\Services;

use App\Models\Filial;
use App\Models\MetasFiliais;
use App\Models\MetasVendedores;
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
        $this->mes = '05'; //Carbon::now()->format('m');
        $this->ano = '2024'; //Carbon::now()->format('Y');

    }

    public function tendencia($total)
    {
        $totalDias = $this->vendas->query()
            ->select('data_pedido')
            ->whereMonth('data_pedido', '=', '05')
            ->groupBy('data_pedido')
            ->get();


        $media = floatVal($total) / count($totalDias);

        return $media * count($totalDias);
    }

    public function meta($mes, $ano)
    {
        $filiais_ids = Venda::query()
            ->select('filial_id')
            ->where('tipo_pedido', 'Venda')
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->groupBy('filial_id')
            ->get();

        $meta = MetasFiliais::query()
            ->selectRaw('sum(meta_faturamento) as meta_faturamento, sum(meta_acessorios) as meta_acessorios, sum(meta_aparelhos) as meta_aparelhos')
            ->whereIn('filial_id', $filiais_ids)
            ->where('mes', $mes)
            ->where('ano', $ano)
            ->get();
        return $meta->toArray();
    }

    public function metaFilial($filial_id, $mes, $ano)
    {

        return MetasFiliais::query()
            ->where('filial_id', $filial_id)
            ->where('mes', $mes)
            ->where('ano', $ano)
            ->first();
    }
    public function metaVendedor($vendedor_id, $mes, $ano)
    {
        $meta = MetasVendedores::query()
            ->where('vendedor_id', $vendedor_id)
            ->where('mes', $mes)
            ->where('ano', $ano)
            ->first();
        return $meta;
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
            ->where('fabricante', '<>', '')
            ->whereMonth('data_pedido', '=', '05')
            ->groupBy('fabricante')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
    }

    public function faturamento($mes, $ano): float
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

        $total = $this->vendas->query()
            ->where('filial_id', $filial_id)
            ->whereMonth('data_pedido', '=', '05')
            ->sum('valor_caixa');


        return floatVal($total);
    }

    public function faturamentoFilialMensal($filial_id, $mes, $ano)
    {

        $total = $this->vendas->query()
            ->where('filial_id', $filial_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->sum('valor_caixa');


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

    public function faturamentoVendedorMensal($vendedor_id, $mes, $ano)
    {
        $total = $this->vendas->query()
            ->where('vendedor_id', $vendedor_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->sum('valor_caixa');


        return floatVal($total);
    }

    public function tendenciaFilial($filial_id, $mes, $ano, $total)
    {

        $totalDias = $this->vendas->query()
            ->select('data_pedido')
            ->where('filial_id', $filial_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->groupBy('data_pedido')
            ->get();


        $media = $total === 0 ? 0 : floatval($total) / count($totalDias);


        return $media * count($totalDias);
    }

    public function tendenciaFilialMensal($filial_id, $mes, $ano)
    {
        $total = $this->vendas->query()
            ->where('filial_id', $filial_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->sum('valor_caixa');


        $totalDias = $this->vendas->query()
            ->select('data_pedido')
            ->where('filial_id', $filial_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->groupBy('data_pedido')
            ->get();



        $media = $total === 0 ? 0 : floatval($total) / count($totalDias);


        return $media * count($totalDias);
    }

    public function tendenciaDiaria($filial_id, $data_pedido)
    {

        $firstDay = Carbon::parse($data_pedido)->startOfMonth();
        $now = Carbon::parse($data_pedido);
        $mediaDia = $firstDay->diffInDays($now) + 1;

        $totalDias = 28;


        $vendas = $this->vendas->query()
            ->selectRaw('sum(valor_caixa) as faturamento')
            ->where('filial_id', $filial_id)
            ->whereBetween('data_pedido', [$firstDay, $data_pedido])
            ->get();


        $media = floatval($vendas[0]->faturamento) / $mediaDia;
        return $media * $totalDias;
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

    public function tendenciaVendedorMensal($vendedor_id, $mes, $ano)
    {
        $total = $this->vendas->query()
            ->where('vendedor_id', $vendedor_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->sum('valor_caixa');


        $totalDias = $this->vendas->query()
            ->select('data_pedido')
            ->where('vendedor_id', $vendedor_id)
            ->whereMonth('data_pedido', '=', $mes)
            ->whereYear('data_pedido', '=', $ano)
            ->groupBy('data_pedido')
            ->get();


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

    public function totalChip()
    {
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

    public function acessoriosFilial($filial, $mes, $ano)
    {

        $total = Cache::remember('acessoriosFilial_' . $filial . '-' . $mes . '-' . $ano, 60, function () use ($filial, $mes, $ano) {
            return $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->where('filial_id', $filial)
                ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
                ->whereMonth('data_pedido', '=', $mes)
                ->whereYear('data_pedido', '=', $ano)
                ->sum('valor_caixa');
        });

        return floatVal($total);
    }

    public function aparelhosFilial($filial, $mes, $ano)
    {

        $total = Cache::remember('aparelhosFilial_' . $filial . '-' . $mes . '-' . $ano, 60, function () use ($filial, $mes, $ano) {
            return $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->where('filial_id', $filial)
                ->where('grupo_estoque', 'APARELHO')
                ->whereMonth('data_pedido', '=', $mes)
                ->whereYear('data_pedido', '=', $ano)
                ->sum('base_faturamento_compra');
        });

        return floatVal($total);
    }

    public function chipsFilial($filial, $mes, $ano)
    {

        $total = Cache::remember('chipsFilial_' . $filial . '-' . $mes . '-' . $ano, 60, function () use ($filial, $mes, $ano) {
            return $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->where('filial_id', $filial)
                ->where('grupo_estoque', 'CHIP')
                ->whereMonth('data_pedido', '=', $mes)
                ->whereYear('data_pedido', '=', $ano)
                ->sum('valor_caixa');
        });

        return floatVal($total);
    }

    public function recargaFilial($filial, $mes, $ano)
    {

        $total = Cache::remember('recargaFilial_' . $filial . '-' . $mes . '-' . $ano, 60, function () use ($filial, $mes, $ano) {
            return $this->vendas->query()
                ->where('tipo_pedido', 'Venda')
                ->where('filial_id', $filial)
                ->whereIn('grupo_estoque', ['RECARGA', 'RECARGA GWCEL'])
                ->whereMonth('data_pedido', '=', $mes)
                ->whereYear('data_pedido', '=', $ano)
                ->sum('valor_caixa');
        });

        return floatVal($total);
    }

    public function totalRecarga()
    {
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereIn('grupo_estoque', ['RECARGA ELETRONICA', 'RECARGA GWCEL'])
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_caixa');
    }

    public function totalFranquia()
    {
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_franquia');
    }

    public function totalAcessorios()
    {
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('valor_caixa');
    }

    public function totalAparelhos()
    {
        return $this->vendas->query()
            ->where('tipo_pedido', 'Venda')
            ->where('grupo_estoque', 'APARELHO')
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->sum('base_faturamento_compra');
    }

    public function totalFaturamento()
    {
        return $this->totalAcessorios() + $this->totalAparelhos() + $this->totalChip() + $this->totalFranquia();
    }

    public function totalPlanos($data)
    {
        $modalidade = explode(';', $data->modalidade_venda);
        $plano_habilitacao = explode(';', $data->plano_habilitacao);
        $grupo_estoque = null;
        $campo_valor = $data->campo_valor;

        $vendas = Venda::query()
            ->selectRaw('count(*) as gross,sum(' . $campo_valor . ') as total')
            ->whereIn('modalidade_venda', $modalidade)
            ->whereIn('plano_habilitacao', $plano_habilitacao)
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->get();

        return $vendas;
    }

    public function totalPlanosFilial($filial_id, $data)
    {
        $modalidade = explode(';', $data->modalidade_venda);
        $plano_habilitacao = explode(';', $data->plano_habilitacao);
        $grupo_estoque = null;
        $campo_valor = $data->campo_valor;

        $vendas = Venda::query()
            ->selectRaw('count(*) as gross,sum(' . $campo_valor . ') as total')
            ->whereIn('modalidade_venda', $modalidade)
            ->whereIn('plano_habilitacao', $plano_habilitacao)
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->where('filial_id', $filial_id)
            ->get();

        return $vendas;
    }

    public function totalPlanosVendedor($vendedor_id, $data)
    {
        $modalidade = explode(';', $data->modalidade_venda);
        $plano_habilitacao = explode(';', $data->plano_habilitacao);
        $grupo_estoque = null;
        $campo_valor = $data->campo_valor;

        $vendas = Venda::query()
            ->selectRaw('count(*) as gross,sum(' . $campo_valor . ') as total')
            ->whereIn('modalidade_venda', $modalidade)
            ->whereIn('plano_habilitacao', $plano_habilitacao)
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->where('vendedor_id', $vendedor_id)
            ->get();

        return $vendas;
    }
}
