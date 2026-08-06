<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_colaborador', 'fecha_inicio', 'fecha_fin', 'motivo', 'id_tipo', 'estado', 'id_usuario_creacion', 'fecha_creacion', 'id_usuario_modificacion', 'fecha_modificacion', 'hora_inicio', 'hora_fin'
    ];
    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'id_colaborador');
    }
    public function tipo()
    {
        return $this->belongsTo(TipoPermiso::class, 'id_tipo');
    }
}