<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaRegistroFecha extends Model
{
    use HasFactory;

    protected $table = 'capacitacion_registro_fecha';
    public $timestamps = false;

    protected $fillable = [
        'id_capacitacion_registro',
        'dia',
        'mes',
        'estado'
    ];

    public function cronograma()
    {
        return $this->belongsTo(CronogramaRegistro::class, 'id_capacitacion_registro', 'id');
    }
}
