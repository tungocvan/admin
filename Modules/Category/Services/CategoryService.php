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
        // 🔥 VALIDATE TREE LOOP
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
        // 🔥 SLUG AUTO
        // =========================
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // =========================
        // 🔥 IMAGE UPLOAD
        // =========================
        if (!empty($data['newImage'])) {

            if (!empty($data['oldImage'])) {
                Storage::disk('public')->delete($data['oldImage']);
            }

            $data['image'] = $data['newImage']->store('categories', 'public');
        }

        unset($data['newImage'], $data['oldImage']);

        // =========================
        // 🔥 SAVE
        // =========================
        return Category::updateOrCreate(
            ['id' => $id],
            $data
        );
    }
}