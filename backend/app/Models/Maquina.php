<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    protected $table = 'tbl_maquina';
    protected $primaryKey = 'maquina_id';
    public $timestamps = false;
    
    protected $fillable = [
        'maquina_codigo', 'maquina_descripcion', 'maquina_marca', 'maquina_modelo',
        'maquina_serie', 'maquina_marca_motor', 'maquina_serie_motor', 'maquina_exigencias',
        'maquina_voltaje', 'maquina_tipo_corriente', 'maquina_anio_compra', 'maquina_vida_util',
        'maquina_imagen', 'maquina_fecha_registro', 'maquina_tipo', 'maquina_ubicacion',
        'maquina_estado', 'precio_compra', 'proveedor'
    ];
}
