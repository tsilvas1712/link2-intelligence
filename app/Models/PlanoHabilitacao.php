<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoHabilitacao extends Model
{
    protected $table = 'plano_habilitacaos';

    protected $fillable = [
        'nome',
        'descricao',
    ];

}
