<?php

namespace Modules\Users\Http\Controllers\Api;
use App\Http\Controllers\Controller;


class UsersController extends Controller
{
   public function index()
    {
        return response()->json([
            'status' => 'Api Users success',            
        ]);
    }  

}
