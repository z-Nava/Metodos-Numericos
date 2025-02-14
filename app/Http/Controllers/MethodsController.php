<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MethodsController extends Controller
{
    public function index()
    {
        return view('methods-views.euler-method');
    }

    public function calculateEuler(Request $request)
    {
        $x0 = $request->input('x0');
        $y0 = $request->input('y0');
        $h = $request->input('h');
        $n = $request->input('n');

        $result = $this->eulerMejorado($x0, $y0, $h, $n);
        return view('methods-views.euler-method', ['result' => $result]);
    }

    private function eulerMejorado($x0, $y0, $h, $n)
    {
        $x = $x0;
        $y = $y0;
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            $k1 = $h * $this->f($x, $y);
            $k2 = $h * $this->f($x + $h, $y + $k1);
            $y = $y + 0.5 * ($k1 + $k2);
            $x = $x + $h;
            $result[] = ['x' => $x, 'y' => $y];
        }

        return $result;
    }

    private function f($x, $y)
    {
        // Define aquí tu función diferencial
        return $x + $y;
    }
    
}
