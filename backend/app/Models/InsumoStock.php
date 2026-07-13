<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsumoStock extends Model
{
    protected $table = 'insumo_stock';
    public $timestamps = false;

    protected $fillable = [
        'id_insumo',
        'codigo_unidad',
        'stock',
        'precio',
        'id_proveedor',
        'descripcion',
        'fecha'
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'id_insumo');
    }

    public function provider()
    {
        return $this->belongsTo(Person::class, 'id_proveedor');
    }

    public function unit()
    {
        return $this->belongsTo(Unidad::class, 'codigo_unidad', 'codigo');
    }
}
