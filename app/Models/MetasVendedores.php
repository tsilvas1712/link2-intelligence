<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetasVendedores extends Model
{
    protected $table = "metas_vendedores";

    protected $fillable = [
        'vendedor_id',
        'meta_faturamento',
        'meta_acessorios',
        'meta_aparelhos',
        'meta_pos',
        'meta_gross_pos',
        'meta_pre',
        'meta_gross_pre',
        'meta_controle',
        'meta_gross_controle',
        'mes',
        'ano'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }
}
