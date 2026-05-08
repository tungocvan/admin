<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Website\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Trang danh sách bài viết (Blog Listing)
     */
    public function index()
    {
        return view('Website::pages.blog.index'); // View này chứa @livewire('website.post.post-list')
    }

    public function detail($slug)
    {
        // Ở đây ta chỉ cần trả về view, Livewire sẽ tự lo logic query dựa trên slug truyền vào
        return view('Website::pages.blog.detail', compact('slug'));
    }
}
