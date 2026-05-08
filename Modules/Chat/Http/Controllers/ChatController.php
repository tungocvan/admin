<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        // Trả về view layout admin, bên trong sẽ chứa Livewire component
        return view('Chat::pages.chat.index');
    }
}