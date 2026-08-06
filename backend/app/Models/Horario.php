<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'id_usuario_creacion',
        'fecha_creacion',
        'id_usuario_modificacion',
        'fecha_modificacion',
        'tolerancia_min'
    ];
    public function dias()
    {
        return $this->hasMany(HorarioDias::class, 'id_horario', 'id');
    }
}
