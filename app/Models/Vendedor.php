<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';
    protected $fillable = ['nome', 'cpf'];

    public function dados()
    {
        return $this->hasMany(DadosVendedor::class);
    }
}
