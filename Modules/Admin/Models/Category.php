<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    // ======================
    // CONFIG
    // ======================

    protected $fillable = [
        'name',
        'slug',
        'url',
        'icon',
        'can',
        'type',
        'parent_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ======================
    // RELATIONSHIPS
    // ======================

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order');
    }

    // ======================
    // SCOPES (CHỈ GIỮ CẦN THIẾT)
    // ======================

    public function scopeMenu(Builder $query): Builder
    {
        return $query->where('type', 'menu');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }


    public static function clearMenuCache(): void
    {
        Cache::forget(config('menu.cache.key', 'admin.menus'));
    }

    // ======================
    // MODEL EVENTS (AUTO CACHE INVALIDATION)
    // ======================

    protected static function booted(): void
    {
        static::saved(fn() => self::clearMenuCache());
        static::deleted(fn() => self::clearMenuCache());
    }
        // ======================
    // CACHE (CORE)
    // ======================

// public static function getMenuTreeCached()
// {
//     $key = config('menu.cache.key', 'admin.menus');
//     $ttl = config('menu.cache.ttl', 3600);

//     return Cache::remember($key, $ttl, function () {
//         return self::menu()
//             ->with(['children' => function ($q) {
//                 $q->with(['children' => function ($q2) {
//                     $q2->with('children'); // hỗ trợ nhiều cấp
//                 }])->orderBy('sort_order');
//             }])
//             ->whereNull('parent_id')
//             ->orderBy('sort_order')
//             ->get(); // 🔥 KHÔNG toArray()
//     });
// }

}
