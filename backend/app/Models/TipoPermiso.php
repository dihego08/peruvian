<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    protected $table = 'tipos_permisos';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'tipo'
    ];
}