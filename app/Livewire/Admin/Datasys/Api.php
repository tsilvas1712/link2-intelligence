<?php

namespace App\Livewire\Admin\Datasys;

use App\Models\Certificado;
use Livewire\Component;

class Api extends Component
{
    public $datasys_key;
    public $datasys_validate;

    public function mount(){
        $certificado = Certificado::query()->first();

        if($certificado){
            $this->datasys_key = $certificado->api_key;
            $this->datasys_validate = $certificado->validateAt;
        }

    }
    public function render()
    {
        return view('livewire.admin.datasys.api');
    }

    public function save(){
      $certificado = Certificado::query()->first();

      if(!$certificado){
        Certificado::create([
            "api_key" => $this->datasys_key,
            "validateAt" => $this->datasys_validate
        ]);
      }

      $certificado->api_key = $this->datasys_key;
      $certificado->validateAt = $this->datasys_validate;
      $certificado->save();

      $this->mount();
    }
}
