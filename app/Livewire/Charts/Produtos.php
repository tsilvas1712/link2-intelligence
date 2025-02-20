<?php

namespace App\Livewire\Charts;

use Livewire\Component;

class Produtos extends Component
{
    public $data = [];

    public function mount($data)
    {
        $this->data = $data;
    }
    public function render()
    {
        return view('livewire.charts.produtos');
    }
}
