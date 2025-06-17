<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadeVenda extends Model
{
    protected $table = 'modalidade_vendas';

    protected $fillable = [
        'nome',
        'descricao',
    ];

}
