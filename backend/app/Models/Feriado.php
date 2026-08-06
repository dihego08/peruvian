<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    protected $table = 'feriados';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'fecha', 'descripcion', /*'id_tipo', */'estado', 'id_usuario_creacion', 'fecha_creacion', 'id_usuario_modificacion', 'fecha_modificacion'
    ];
    /*public function tipo()
    {
        return $this->belongsTo(TipoFeriado::class, 'id_tipo');
    }*/
}