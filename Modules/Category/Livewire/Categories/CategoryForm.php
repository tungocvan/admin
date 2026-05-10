<?php

namespace Modules\Category\Livewire\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryType;
use Modules\Category\Services\CategoryService;

class CategoryForm extends Component
{
    use WithFileUploads;

    public $categoryId;

    public $name, $slug, $type, $parent_id;
    public $sort_order = 0;
    public $is_active = true;

    public $newImage, $oldImage;

    protected CategoryService $service;

    // =========================
    // TYPE MODAL
    // =========================
    public $showTypeModal = false;

    public $selectedType;
    public $editTitle;
    public $editIcon;
    public $editActive = true;

    public $newType;
    public $newTypeTitle;
    public $newTypeIcon;

    public function boot(CategoryService $service)
    {
        $this->service = $service;
    }

    public function mount($id = null)
    {
        if ($id) {
            $c = Category::findOrFail($id);

            $this->fill([
                'categoryId' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'type' => $c->type,
                'parent_id' => $c->parent_id,
                'sort_order' => $c->sort_order,
                'is_active' => $c->is_active,
                'oldImage' => $c->image
            ]);
        } else {
            $this->type = CategoryType::where('is_active', true)->value('type');
        }
    }

    // =========================
    // TYPES
    // =========================
    public function getTypesProperty()
    {
        return CategoryType::orderBy('sort_order')->get();
    }

    // =========================
    // PARENTS
    // =========================
    public function getParentsProperty()
    {
        $list = Category::where('type', $this->type)
            ->when($this->categoryId, fn($q) => $q->where('id', '!=', $this->categoryId))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->service->buildTree($list);
    }

    // =========================
    // MODAL
    // =========================
    public function openTypeModal()
    {
        $this->reset([
            'selectedType',
            'editTitle',
            'editIcon',
            'editActive',
            'newType',
            'newTypeTitle',
            'newTypeIcon'
        ]);

        $this->showTypeModal = true;
    }

    public function updatedSelectedType($value)
    {
        if (!$value) {
            $this->reset(['editTitle', 'editIcon', 'editActive']);
            return;
        }

        $type = CategoryType::where('type', $value)->first();

        if ($type) {
            $this->editTitle = $type->title;
            $this->editIcon = $type->icon;
            $this->editActive = (bool) $type->is_active;
        }
    }

    // =========================
    // CREATE TYPE
    // =========================
    public function createType()
    {
        $this->validate([
            'newType' => 'required|alpha_dash|unique:category_types,type',
            'newTypeTitle' => 'required|min:2',
        ]);

        CategoryType::create([
            'type' => $this->newType,
            'title' => $this->newTypeTitle,
            'icon' => $this->newTypeIcon,
            'is_active' => true,
            'sort_order' => CategoryType::max('sort_order') + 1,
        ]);

        $this->type = $this->newType;
        $this->showTypeModal = false;
    }

    // =========================
    // UPDATE TYPE
    // =========================
    public function updateType()
    {
        $type = CategoryType::where('type', $this->selectedType)->firstOrFail();

        $type->update([
            'title' => $this->editTitle,
            'icon' => $this->editIcon,
            'is_active' => $this->editActive,
        ]);
        $this->dispatch(
            'notify',
            content: "Cập nhật loại thành công",
            type: 'success'
        );
    }

    // =========================
    // DELETE TYPE
    // =========================
    public function deleteType()
    {
        $type = CategoryType::where('type', $this->selectedType)->firstOrFail();

        $hasCategory = Category::where('type', $type->type)->exists();

        if ($hasCategory) {
            $this->dispatch(
                'notify',
                content: "Không thể xóa vì đã có danh mục",
                type: 'error'
            );
            return;
        }

        $type->delete();

        $this->dispatch(
            'notify',
            content: "Xóa loại thành công",
            type: 'success'
        );

        // reset select
        $this->selectedType = null;
    }

    // =========================
    // EVENTS
    // =========================
    public function updatedName()
    {
        if (!$this->categoryId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function updatedType()
    {
        $this->parent_id = null;
    }

    // =========================
    // SAVE
    // =========================
    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',
            'type' => 'required|exists:category_types,type',
        ]);

        $this->service->save([
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'newImage' => $this->newImage,
            'oldImage' => $this->oldImage,
        ], $this->categoryId);

        return redirect()->route('admin.category.index');
    }

    public function render()
    {
        return view('Category::livewire.categories.category-form');
    }
}
