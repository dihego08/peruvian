<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $table = 'sell';
    public $timestamps = false;
    protected $guarded = [];

    // Relationships
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'sell_id');
    }
}
