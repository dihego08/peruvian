<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;

    protected $fillable = [
        'image', 'kind', 'code', 'brand_id', 'width', 'height', 'weight', 'barcode',
        'name', 'description', 'price_in', 'price_in_2', 'price_out', 'user_id',
        'presentation', 'unit', 'category_id', 'inventary_min', 'created_at',
        'imgbordado', 'cliente_id', 'prebor_in', 'prebor_out', 'fecact', 'large', 'secuencia', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function client()
    {
        return $this->belongsTo(Person::class, 'cliente_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
