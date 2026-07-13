<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';
    public $timestamps = false;
    protected $fillable = ['codigo', 'descripcion'];
}
