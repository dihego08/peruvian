<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaquinaMantenimiento extends Model
{
    protected $table = 'tbl_maq_mtto';
    protected $primaryKey = 'maq_mtto_id';
    public $timestamps = false;

    protected $fillable = [
        'maq_mtto_tipo', 'maq_mtto_fecha', 'maq_mtto_reponsable', 'maq_mtto_observacion',
        'maquina_id', 'maq_mtto_costo', 'tipo_mantenimiento'
    ];
}
