<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionDetalle extends Model
{
    protected $table = 'cotizacion_detalle';
    public $timestamps = false;

    protected $fillable = [
        'codigo_cotizacion',
        'id_producto',
        'cantidad',
        'imagen',
        'imagen_2',
        'costo',
        'descripcion',
        'servicios',
        'nombre_producto'
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'codigo_cotizacion', 'codigo');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_producto');
    }
}
