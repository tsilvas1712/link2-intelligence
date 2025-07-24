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

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }

    public function vendas_atual()
    {
        return $this->hasMany(VendaAtual::class);
    }

    public function metas()
    {
        return $this->hasMany(MetasVendedores::class);
    }
}