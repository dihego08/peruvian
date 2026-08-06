<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioDias extends Model
{
    protected $table = 'horario_dias';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
         	 	'id_horario',
         	 	'dia_semana',
         	 	'hora_entrada',
         	 	'hora_salida',
         	 	'descanso',
         	 	'activo',
         	 	'id_usuario_creacion',
         	 	'fecha_creacion',
         	 	'id_usuario_modificacion',
         	 	'fecha_modificacion',
                'hora_fin_refrigerio',
                'hora_inicio_refrigerio'
    ];
    /*public function tipo()
    {
        return $this->belongsTo(TipoFeriado::class, 'id_tipo');
    }*/
}
