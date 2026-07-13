<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operation';
    public $timestamps = false;
    protected $guarded = [];

    // Relationships
    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
