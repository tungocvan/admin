<?php

namespace Modules\Pharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PharmaController extends Controller
{
    public function __construct()
    {
       // $this->middleware('permission:pharma-list|pharma-create|pharma-edit|pharma-delete', ['only' => ['index','show']]);
       // $this->middleware('permission:pharma-create', ['only' => ['create','store']]);
       // $this->middleware('permission:pharma-edit', ['only' => ['edit','update']]);
       // $this->middleware('permission:pharma-delete', ['only' => ['destroy']]);
    }

    public function ThongTu()
    {
        return view('Pharma::thongtu');
    }
    public function TraCuu()
    {
        return view('Pharma::tracuu');
    }
    public function HoSoSanPham()
    {
        return view('Pharma::hssp');
    }
    public function TanDuoc()
    {
        return view('Pharma::tanduoc');
    }
    public function DongY()
    {
        return view('Pharma::dongy');
    }
}
