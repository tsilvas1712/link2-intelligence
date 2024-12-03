<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DadoFilial;

class Filial extends Model
{
    protected $table = 'filials';
    protected $fillable = ['filial'];

    public function dados()
    {
        return $this->hasMany(DadoFilial::class);
    }

    public function venda()
    {
        return $this->hasOne(Venda::class);
    }
}
