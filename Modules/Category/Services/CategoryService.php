<?php

namespace Modules\Category\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;

class CategoryService
{
    public function save(array $data, $id = null)
    {
        // =========================
        // TREE VALIDATION
        // =========================
        if (!empty($data['parent_id']) && $id) {

            if ($data['parent_id'] == $id) {
                throw new \Exception('Không thể chọn chính nó làm cha');
            }

            $parent = Category::with('childrenRecursive')->find($data['parent_id']);

            if ($parent && in_array($id, $parent->getAllChildrenIds())) {
                throw new \Exception('Không thể chọn danh mục con làm cha');
            }
        }

        // =========================
        // SLUG
        // =========================
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // =========================
        // IMAGE
        // =========================
        if (!empty($data['newImage'])) {

            if (!empty($data['oldImage'])) {
                Storage::disk('public')->delete($data['oldImage']);
            }

            $data['image'] = $data['newImage']->store('categories', 'public');
        }

        unset($data['newImage'], $data['oldImage']);

        return Category::updateOrCreate(
            ['id' => $id],
            $data
        );
    }

    // =========================
    // TREE BUILDER
    // =========================
    public function buildTree($items, $parent = null, $prefix = '')
    {
        $res = [];

        foreach ($items as $item) {
            if ($item->parent_id == $parent) {

                $item->view_name = $prefix . $item->name;
                $res[] = $item;

                $res = array_merge(
                    $res,
                    $this->buildTree($items, $item->id, $prefix . '-- ')
                );
            }
        }

        return $res;
    }
}