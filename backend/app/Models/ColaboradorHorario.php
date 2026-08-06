<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColaboradorHorario extends Model
{
    protected $table = 'colaborador_horarios';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_colaborador', 'id_horario', 'fecha_inicio', 'fecha_fin', 'estado', 'id_usuario_creacion', 'fecha_creacion', 'id_usuario_modificacion', 'fecha_modificacion'
    ];
    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'id_colaborador');
    }
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'id_horario');
    }
}