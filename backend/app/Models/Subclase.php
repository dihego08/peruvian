<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subclase extends Model
{
    protected $table = 'subclases';
    public $timestamps = false;
    protected $fillable = ['codigo', 'descripcion'];
}
