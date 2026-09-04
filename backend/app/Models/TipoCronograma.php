<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCronograma extends Model
{
    use HasFactory;

    protected $table = 'tipo_cronogramas';
    public $timestamps = false;

    protected $fillable = [
        'tipo_cronograma'
    ];
}
