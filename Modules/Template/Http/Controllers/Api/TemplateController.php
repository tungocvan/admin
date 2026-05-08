<?php

namespace Modules\Template\Http\Controllers\Api;
use App\Http\Controllers\Controller;


class TemplateController extends Controller
{
   public function index()
    {
        return response()->json([
            'status' => 'Api Template success',            
        ]);
    }  

}
