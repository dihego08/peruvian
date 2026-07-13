<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $fillable = ['cargo', 'id_referencia'];
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Person::class, 'id_referencia');
    }
}
