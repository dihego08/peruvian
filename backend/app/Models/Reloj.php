<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Reloj extends Model
{
    protected $table = 'relojes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nombre', 'ip', 'puerto', 'ubicacion', 'estado', 'id_usuario_creacion', 'fecha_creacion', 'id_usuario_modificacion', 'fecha_modificacion'
    ];
}
