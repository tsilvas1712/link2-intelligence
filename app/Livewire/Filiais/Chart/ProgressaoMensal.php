<?php

namespace App\Livewire\Filiais\Chart;

use Livewire\Component;

class ProgressaoMensal extends Component
{
    public $data = [];

    public function mount($data)
    {
        $this->data = $data;
    }
    public function render()
    {
        return view('livewire.filiais.chart.progressao-mensal');
    }
}
