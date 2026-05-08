<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;



class CategoryController extends Controller
{
    public function index() {
        return view('Category::pages.categories.index');
    }
    public function create() {
        return view('Category::pages.categories.create');
    }
    public function edit($id) {
        return view('Category::pages.categories.edit', compact('id'));
    }
}
