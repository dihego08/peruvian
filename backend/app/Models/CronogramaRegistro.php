<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaRegistro extends Model
{
    use HasFactory;

    protected $table = 'capacitacion_registro';
    public $timestamps = false;

    protected $fillable = [
        'curso',
        'areas',
        'mes',
        'anio',
        'responsable',
        'estado',
        'dia',
        'fecha',
        'eficacia',
        'id_tipo'
    ];

    public function fechas()
    {
        return $this->hasMany(CronogramaRegistroFecha::class, 'id_capacitacion_registro', 'id');
    }
}
