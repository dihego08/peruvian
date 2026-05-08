<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizacion';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'fecha_creacion',
        'tiempo_entrega',
        'obervacion',
        'servicios',
        'sub_total',
        'total',
        'igv',
        'person_id',
        'cliente',
        'validez',
        'forma_pago',
        'tallas_especiales',
        'asesor_comercial',
        'asesor_celular',
        'igv_incluye'
    ];

    public function details()
    {
        return $this->hasMany(CotizacionDetalle::class, 'codigo_cotizacion', 'codigo');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
