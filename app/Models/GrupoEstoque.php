<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoEstoque extends Model
{
    //
    protected $table = 'grupo_estoques';
    protected $fillable = [
        'nome',
        'descricao',
    ];
}
