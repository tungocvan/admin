<?php

namespace Modules\Category\Livewire\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryType;
use Modules\Category\Services\CategoryService;

class CategoryForm extends Component
{
    use WithFileUploads;

    public $categoryId;

    public $name;
    public $slug;
    public $type;
    public $parent_id;

    public $sort_order = 0;
    public $is_active = true;

    public $newImage;
    public $oldImage;

    protected CategoryService $service;

    public function boot(CategoryService $service)
    {
        $this->service = $service;
    }

    // =========================
    // INIT
    // =========================
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
    // COMPUTED
    // =========================
    public function getTypesProperty()
    {
        return CategoryType::where('is_active', true)->get();
    }

    public function getParentsProperty()
    {
        $list = Category::where('type', $this->type)
            ->when($this->categoryId, fn($q) => $q->where('id', '!=', $this->categoryId))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->buildTree($list);
    }

    // =========================
    // TREE BUILDER
    // =========================
    private function buildTree($items, $parent = null, $prefix = '')
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
    // VALIDATION
    // =========================
    protected function rules()
    {
        return [
            'name' => 'required|min:2',

            'slug' => [
                'nullable',
                Rule::unique('categories', 'slug')
                    ->ignore($this->categoryId)
                    ->where(fn($q) => $q->where('type', $this->type))
            ],

            'type' => 'required|exists:category_types,type',

            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(fn($q) =>
                    $q->where('type', $this->type)
                ),
            ],

            'newImage' => 'nullable|image|max:2048',
        ];
    }

    // =========================
    // SAVE
    // =========================
    public function save()
    {
        $this->validate();

        try {
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

        } catch (\Throwable $e) {
            $this->addError('parent_id', $e->getMessage());
        }
    }

    public function render()
    {
        return view('Category::livewire.categories.category-form');
    }
}