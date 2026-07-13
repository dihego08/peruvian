<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    public $timestamps = false;

    protected $fillable = [
        'no',
        'name',
        'lastname',
        'address1',
        'email1',
        'phone1',
        'is_active_access',
        'password',
        'kind',
        'credit_limit',
        'has_credit',
        'created_at',
        'wsp',
        'banco',
        'nro_cuenta',
        'tipo_pago',
        'tipo_cuenta',
        'tipo_moneda',
        'forma_envio',
        'id_insumo',
        'company'
    ];

    // kind: 1=Client, 2=Provider, 3=Contact
    public function scopeClients($query)
    {
        return $query->where('kind', 1);
    }

    public function scopeProviders($query)
    {
        return $query->where('kind', 2);
    }
}
