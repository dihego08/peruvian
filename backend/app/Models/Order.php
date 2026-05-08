<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order_cabecera';
    public $timestamps = false; // Legacy table, we will handle created_at manually if needed, or set it to false and let the controller set it.

    protected $fillable = [
        'codigo',
        'tiempo_entrega',
        'person_id',
        'estado',
        'fecha_entrega',
        'fecha_creacion',
        'num_contrato',
        'fecha_entrega_real',
        'guia_remision',
        'nombre_modelo',
        'comentario',
        'imagen_alt',
        'total'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'codigo_cabecera', 'codigo');
    }
}
