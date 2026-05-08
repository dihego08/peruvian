<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuEntity extends Model
{
    protected $table = 'menus_entidades';
    protected $fillable = ['idUsuario', 'idMenu'];
    public $timestamps = false;
}
