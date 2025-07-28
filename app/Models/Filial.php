<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'filials';
    protected $fillable = ['filial'];


    public function venda()
    {
        return $this->hasOne(Venda::class);
    }

    public function venda_atual()
    {
        return $this->hasOne(VendaAtual::class);
    }

    public function metas()
    {
        return $this->hasMany(MetaGroup::class);
    }
}
