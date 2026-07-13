<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppMenu extends Model
{
    protected $table = 'app_menus';

    protected $fillable = [
        'parent_id',
        'label',
        'route',
        'icon',
        'sort_order',
        'module_key',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'app_menu_user',
            'app_menu_id',
            'user_id'
        )->withTimestamps(false);
    }
}
