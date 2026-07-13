<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    public $timestamps = false;

    protected $fillable = [
        'codigo', 'serie', 'numeracion', 'id_proveedor', 'proveedor', 
        'igv', 'gravado', 'exonerado', 'otros_no_gravado', 'total', 
        'tipo_documento', 'id_forma_pago', 'fecha_creacion', 'fproceso',
        'fecha_detraccion', 'numero_detraccion', 'tipo_cambio', 
        'fecha_comprobante', 'serie_comprobante', 'documento_comprobante'
    ];

    public function provider()
    {
        return $this->belongsTo(Person::class, 'id_proveedor');
    }

    public function details()
    {
        return $this->hasMany(CompraDetalle::class, 'id_compra');
    }
}
