<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marcacion extends Model
{
    protected $table = 'marcaciones';
    protected $primaryKey = 'id';
    //public $timestamps = false;
    protected $fillable = [
        'id',
        'dni',
        'fecha_hora',
        'estado',
        'reloj_ip',
        'created_at',
        'updated_at'
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
