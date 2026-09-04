<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table = 'dispositivos';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'cantidad',
        'imagen',
        'observaciones',
        'codigo',
        'responsable',
        'fecha'
    ];

    public function registros()
    {
        return $this->hasMany(RegistroDispositivo::class, 'id_dispositivo', 'id');
    }
}
