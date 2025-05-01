<?php

namespace App\Observers;

use App\Models\Venda;

class VendaObserver
{
    /**
     * Handle the Venda "created" event.
     */
    public function creating(Venda $venda): void
    {
        $venda->modalidade_venda = mb_strtoupper($venda->modalidade_venda, 'UTF-8');
        $venda->tipo_pedido = mb_strtoupper($venda->tipo_pedido, 'UTF-8');
        $venda->plano_habilitacao = mb_strtoupper($venda->plano_habilitacao, 'UTF-8');
        $venda->grupo_estoque = mb_strtoupper($venda->grupo_estoque, 'UTF-8');
        $venda->descricao_comercial = mb_strtoupper($venda->descricao_comercial, 'UTF-8');
        $venda->descricao = mb_strtoupper($venda->descricao, 'UTF-8');
        $venda->familia = mb_strtoupper($venda->familia, 'UTF-8');
        $venda->fabricante = mb_strtoupper($venda->fabricante, 'UTF-8');

        if ($venda->valor_franquia === null) {
            $venda->valor_franquia = $this->getValorFranquia($venda->plano_habilitacao);
        }
        $venda->valor_franquia = $this->getValorFranquia($venda->plano_habilitacao) ?? $venda->valor_franquia;
    }

    /**
     * Handle the Venda "updated" event.
     */
    public function updated(Venda $venda): void
    {
        //
    }

    public function getValorFranquia($plano_habilitado)
    {
        $plano = \App\Models\Plano::where('plano_habilitado', strtoupper($plano_habilitado))->first();

        if ($plano === null) {
            return false;
        }
        return $plano->valor;
    }
}
