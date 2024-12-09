<?php

namespace App\Livewire\Filiais;

use App\Models\Venda;
use App\Models\Filial as FilialModel;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Filial extends Component
{
    public $mes;
    public $ano;

    public $filial_id;

    public $selectedMonth;
    public $meses = [];

    public $vendas;
    public $filial;
    public $chartVendas;
    public $expand = false;

    public function mount($id)
    {
        $this->filial = FilialModel::find($id);



        $this->ano = Carbon::now()->format('Y');
        $this->mes = '05';

        $this->chartVendas = $this->chartVendas();



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
        } //Carbon::now()->format('m');
    }
    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.filiais.filial');
    }

    public function filter()
    {
        $this->chartVendas = $this->chartVendas();
    }

    public function getVendas()
    {
        return Venda::query()
            ->selectRaw('filial_id,data_pedido, sum(valor_caixa) as faturamento')
            ->where('filial_id', $this->filial->id)
            ->when($this->selectedMonth, function ($query, $mes) {
                return $query->whereMonth('data_pedido', $this->selectedMonth);
            })
            ->when(!$this->selectedMonth, function ($query, $mes) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->whereYear('data_pedido', $this->ano)
            ->groupBy(['filial_id', 'data_pedido'])
            ->get();
    }

    public function chartVendas()
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

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->faturamento));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta[0]->meta_faturamento);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,
                'legend' => [
                    'display' => false,
                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Vendas',
                        'data' => $chartData['dataset'],
                        'options' => [],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Tendencia',
                        'data' => $chartData['datasetTendencia'],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                    ],


                ]
            ]
        ];



        return $chart;
    }
}
