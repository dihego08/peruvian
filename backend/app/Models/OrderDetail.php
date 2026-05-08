<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detalle_2';
    public $timestamps = false;

    protected $fillable = [
        'codigo_cabecera',
        'modelo',
        'color',
        '_2', '_4', '_6', '_8', '_10', '_12', '_14', '_16',
        's', 'm', 'l', 'xl', 'xxl',
        'total',
        'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'n7', 'n8', 'n9', 'n10', 'n11', 'n12', 'n13'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'codigo_cabecera', 'codigo');
    }
}
