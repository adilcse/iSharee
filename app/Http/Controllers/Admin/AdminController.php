<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        # code...
        return view('admin.dashboard');
    }
    public function profile()
    {
        return view('admin.dashboard');
        # code...
    }
}
