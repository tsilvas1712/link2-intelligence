<?php

namespace App\Livewire\Detalhamento;

use App\Models\Grupo;
use App\Models\Venda;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Grupos extends Component
{
    public $grupo;
    public $mes;
    public $ano;

    public $meses = [];

    public $anos = [];

    public $monthSelected;
    public $yearSelected;

    public $chartVendas;

    public $chartGross;

    public function mount($id)
    {
        $this->grupo = Grupo::query()->where("id", $id)->firstOrFail();
        $this->mes = '06'; //Carbon::now()->format("m");
        $this->ano = Carbon::now()->format("Y");

        $meses = [
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro"
        ];

        foreach ($meses as $key => $mes) {
            $this->meses[] = [
                'id' => $key,
                'name' => $mes,
            ];
        }
        $anoInicial = Carbon::now()->subYears(2)->format('Y');

        for ($i = 0; $i < 10; $i++) {
            $this->anos[] = [
                'id' => $anoInicial + $i,
                'name' =>  $anoInicial + $i,
            ];
        }

        $this->chartVendas = $this->getChartVendas();
        $this->chartGross = $this->getChartGross();
    }
    #[Layout("components.layouts.view")]
    public function render()
    {
        return view('livewire.detalhamento.grupos');
    }

    public function filter()
    {
        $this->chartVendas = $this->getChartVendas();
        $this->chartGross = $this->getChartGross();
    }


    #[Computed]
    public function getVendas()
    {
        $modalidades = explode(';', $this->grupo->modalidade_venda);
        $planos = explode(';', $this->grupo->plano_habilitacao);
        $modalidade_venda = [];
        $plano_habilitacao = [];

        foreach ($planos as $plano) {
            $plano_habilitacao[] = trim($plano);
        }

        foreach ($modalidades as $modalidade) {
            $modalidade_venda[] = trim($modalidade);
        }

        return Venda::query()
            ->selectRaw('filial_id,count(*) as gross, sum(valor_franquia) as total')
            ->whereIn('modalidade_venda', $modalidade_venda)
            ->whereIn('plano_habilitacao', $plano_habilitacao)
            ->when($this->monthSelected, function ($query) {
                $query->whereMonth('data_pedido', $this->monthSelected);
            })
            ->when($this->yearSelected, function ($query) {
                $query->whereYear('data_pedido', $this->yearSelected);
            })
            ->when(!$this->monthSelected, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->yearSelected, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->groupBy('filial_id')
            ->get();
    }

    public function getChartVendas()
    {
        $data = $this->getVendas();

        $imagemTelecom = new ImagemTelecomService(new Venda());

        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);


            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta[0]->meta_pos);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => false,
                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Totais em Vendas',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#046BD9',
                        'backgroundColor' => '#046BD9',
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                        'borderColor' => '#F36F1C',
                        'backgroundColor' => '#F36F1C',
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getChartGross()
    {
        $data = $this->getVendas();

        $imagemTelecom = new ImagemTelecomService(new Venda());

        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);


            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->gross));
            array_push($datasetMeta, $meta[0]->meta_gross_pos);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetMeta'] = $datasetMeta;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => false,
                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Gross',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#046BD9',
                        'backgroundColor' => '#046BD9',
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                        'borderColor' => '#F36F1C',
                        'backgroundColor' => '#F36F1C',
                    ],


                ]
            ]
        ];

        return $chart;
    }
}
