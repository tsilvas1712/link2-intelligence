<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    //

    protected $table = "grupos";
    protected $fillable = ['nome', 'descricao', 'grupo_estoque', 'campo_valor', 'plano_habilitacao', 'modalidade_venda'];


    public function items()
    {
        return $this->hasMany(ItemGrupo::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Category::class);
    }
}
