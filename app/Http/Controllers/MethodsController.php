<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MethodsController extends Controller
{
    public function index()
    {
        return view('methods-views.euler-method');
    }

    
}
