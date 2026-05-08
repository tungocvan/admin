<?php

namespace Modules\Admin\Services;

use Modules\Admin\Models\Category;
use Illuminate\Support\Facades\Cache;

class SidebarService
{
    protected string $cacheKey = 'admin.menus';

    // ======================
    // GET MENUS
    // ======================
    public function getMenus()
    {
        return Cache::remember($this->cacheKey, 3600, function () {

            $menus = Category::query()
                ->select([
                    'id',
                    'name',
                    'url',
                    'icon',
                    'parent_id',
                    'sort_order',
                    'can',
                    'is_active'
                ])
                ->where('type', 'menu')
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->with(['children' => function ($q) {
                    $q->select([
                            'id',
                            'name',
                            'url',
                            'icon',
                            'parent_id',
                            'sort_order',
                            'can',
                            'is_active'
                        ])
                        ->where('is_active', true)
                        ->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();

            return $this->buildTree($menus);
        });
    }

    // ======================
    // SAFE URL NORMALIZER
    // ======================
    protected function normalizeUrl($url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // nếu đã là full URL thì giữ nguyên
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        // đảm bảo bắt đầu bằng /
        return '/' . ltrim($url, '/');
    }

    // ======================
    // BUILD PRE-PROCESSED TREE (ULTRA SAFE)
    // ======================
    protected function buildTree($menus)
    {
        return $menus->map(function ($menu) {

            $children = $menu->children ?? collect();

            return [
                'id'   => $menu->id,
                'name' => $menu->name,

                // ✅ FIX QUAN TRỌNG: luôn là STRING hoặc NULL
                'url'  => $this->normalizeUrl($menu->url),

                'icon' => $menu->icon,
                'can'  => $menu->can,

                'has_children' => $children->isNotEmpty(),

                'children' => $children->map(function ($child) {
                    return [
                        'id'   => $child->id,
                        'name' => $child->name,

                        // ✅ FIX CHILD URL
                        'url'  => $this->normalizeUrl($child->url),

                        'icon' => $child->icon,
                        'can'  => $child->can,
                    ];
                })->values(),
            ];
        })->values();
    }

    // ======================
    // CLEAR CACHE
    // ======================
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}