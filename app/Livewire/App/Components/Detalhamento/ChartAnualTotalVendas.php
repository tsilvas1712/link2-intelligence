<?php

namespace App\Livewire\App\Components\Detalhamento;

use App\Models\Filial;
use App\Models\GrupoEstoque;
use App\Models\Grupo;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Component;

class ChartAnualTotalVendas extends Component
{
    public $meses;
    public $ano;
    public $grupo_id;
    public $data = [];
    public $filial_id;

    public function mount()
    {
        $this->meses = $this->getMeses();
        $this->ano = Carbon::now()->year;
        $this->data = $this->getChartMetas();
    }
    public function render()
    {
        $filial = Filial::find($this->filial_id);

        return view('livewire.app.components.detalhamento.chart-anual-total-vendas', [
            'filial' => $filial,

        ]);
    }

    public function getChartMetas()
    {
        $meses = [
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez',
        ];
        foreach ($this->meses as $mes) {
            $data_inicial = Carbon::parse($this->ano . '-' . $mes['id'] . '-01')->startOfMonth()->format('Y-m-d');
            $data_final = Carbon::parse($data_inicial)->endOfMonth()->format('Y-m-d');
            $vendas = Venda::query()
                ->whereBetween('data_pedido', [$data_inicial, $data_final])
                ->get();



            $meta = 0;
            $grupo_estoque = [];
            $plano_habilitado = [];
            $modalidade_venda = [];

            $grupo = Grupo::find($this->grupo_id);
            if ($grupo->grupo_estoque) {
                $grupo_estoque = GrupoEstoque::query()
                ->whereIn('id', explode(';', $grupo->grupo_estoque) ?? [])
                ->pluck('nome')
                ->toArray();
            }

            if ($grupo->plano_habilitacao) {
                $plano_habilitado = PlanoHabilitacao::query()
                    ->whereIn('id', explode(';', $grupo->plano_habilitacao) ?? [])
                    ->pluck('nome')
                    ->toArray();
            }

            if ($grupo->modalidade_venda) {
                $modalidade_venda = ModalidadeVenda::query()
                    ->whereIn('id', explode(';', $grupo->modalidade_venda) ?? [])
                    ->pluck('nome')
                    ->toArray();
            }


            $totalVendas = $vendas
            ->when($grupo->grupo_estoque, function ($query) use ($grupo_estoque) {
                return $query->whereIn('grupo_estoque', $grupo_estoque);
            })
            ->when($grupo->plano_habilitacao, function ($query) use ($plano_habilitado) {
                return $query->whereIn('plano_habilitacao', $plano_habilitado);
            })
            ->when($grupo->modalidade_venda, function ($query) use ($modalidade_venda) {
                return $query->whereIn('modalidade_venda', $modalidade_venda);
            })
            ->where('filial_id', $this->filial_id)
            ->sum($grupo->campo_valor);




            $chartMetasLabels[] = $meses[$mes['id']];
            $chartMetasDatasets[] = $totalVendas;
            //$meta = $imagemTelecom->meta($mes, $this->ano);
            $chartMetasDatasetsMeta[] = $meta ?? 0;
        }



        return [
            'type' => 'bar',
            'data' => [
                'labels' => $chartMetasLabels,
                'datasets' => [
                    [
                        'name' => 'Tendência',
                        'data' => $chartMetasDatasets,

                    ],
                    [
                        'name' => 'Vendas',
                        'data' => $chartMetasDatasets,

                    ],
                    [
                        'name' => 'Meta',
                        'data' => $chartMetasDatasetsMeta,

                    ],

                ],

            ],
            'horizontal' => false,
        ];
    }

    public function getMeses()
    {
        return [
            ['id' => '01', 'name' => 'Janeiro'],
            ['id' => '02', 'name' => 'Fevereiro'],
            ['id' => '03', 'name' => 'Março'],
            ['id' => '04', 'name' => 'Abril'],
            ['id' => '05', 'name' => 'Maio'],
            ['id' => '06', 'name' => 'Junho'],
            ['id' => '07', 'name' => 'Julho'],
            ['id' => '08', 'name' => 'Agosto'],
            ['id' => '09', 'name' => 'Setembro'],
            ['id' => '10', 'name' => 'Outubro'],
            ['id' => '11', 'name' => 'Novembro'],
            ['id' => '12', 'name' => 'Dezembro'],
        ];
    }
}
