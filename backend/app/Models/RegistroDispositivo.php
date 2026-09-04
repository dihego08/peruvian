<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroDispositivo extends Model
{
    use HasFactory;

    protected $table = 'registro_dispositivo';
    public $timestamps = false;

    protected $fillable = [
        'id_dispositivo',
        'fecha_entrega',
        'recibido_por',
        'cantidad',
        'observaciones',
        'responsable'
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'id_dispositivo', 'id');
    }
}
