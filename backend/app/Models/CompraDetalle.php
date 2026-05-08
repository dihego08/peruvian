<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    protected $table = 'compras_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_compra', 'codigo_compra', 'id_insumo', 'precio', 'cantidad', 'total', 'unidad'
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'id_insumo');
    }
}
