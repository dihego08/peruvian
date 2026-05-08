<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = 'familias';
    public $timestamps = false;
    protected $fillable = ['codigo', 'descripcion'];
}
