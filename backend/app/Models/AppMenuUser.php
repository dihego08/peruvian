<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppMenuUser extends Model
{
    public $timestamps = false;

    protected $table = 'app_menu_user';

    protected $fillable = [
        'app_menu_id',
        'user_id',
    ];
}
