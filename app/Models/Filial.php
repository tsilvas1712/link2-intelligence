<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'filials';
    protected $fillable = ['filial'];

    public function dados()
    {
        return $this->hasMany(DadoFilial::class);
    }
}
