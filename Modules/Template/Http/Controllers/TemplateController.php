<?php

namespace Modules\Template\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        //  $this->middleware('permission:template-list|template-create|template-edit|template-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:template-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:template-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:template-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        // Lấy URL hiện tại
        $currentUrl = url()->current();
         // Lấy path (chỉ phần sau domain)
         $path = request()->path(); // ví dụ: template/dashboard
         // Lấy segment cuối cùng của URI
         $component = request()->segment(count(request()->segments())); // "dashboard"

        return view('Template::template',compact('component'));
    }
    public function adminTemplate()
    {
        // Lấy URL hiện tại
        $currentUrl = url()->current();
         // Lấy path (chỉ phần sau domain)
        $path = request()->path(); // ví dụ: template/dashboard
         // Lấy segment cuối cùng của URI
        $component = request()->segment(count(request()->segments())); // "dashboard"

        return view('Template::template-nolayout',compact('component'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
