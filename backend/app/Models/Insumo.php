<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumos_2';
    public $timestamps = false;

    protected $fillable = [
        'insumo',
        'familia',
        'clase',
        'subclase',
        'codigo',
        'stock', // Some methods update stock here too
        'unidad'
    ];

    public function stocks()
    {
        return $this->hasMany(InsumoStock::class, 'id_insumo');
    }
}
