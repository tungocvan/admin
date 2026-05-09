<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;


class CategoryType extends Model
{
    protected $primaryKey = 'type';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['type','title','icon','is_active'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'type', 'type');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}