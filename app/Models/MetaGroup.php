<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaGroup extends Model
{
    //
    protected $table = 'meta_groups';
    protected $fillable = [
        'grupo_id',
        'filial_id',
        'vendedor_id',
        'valor_meta',
        'quantidade',
    ];
    protected $casts = [
        'valor_meta' => 'decimal:2',
        'quantidade' => 'integer',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'filial_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }
}
