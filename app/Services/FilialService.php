<?php

namespace App\Services;

class FilialService
{
    public $mes;
    public $ano;
    /**
     * Create a new class instance.
     */
    public function __construct($mes, $ano)
    {
        $this->mes = $mes;
        $this->ano = $ano;
    }

    public function teste()
    {
        return [$this->mes, $this->ano];
    }
}
