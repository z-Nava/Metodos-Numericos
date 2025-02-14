<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MethodsController extends Controller
{
    public function indexE()
    {
        return view('methods-views.euler-method');
    }

    public function indexK()
    {
        return view('methods-views.kutta-method');
    }

    public function indexN()
    {
        return view('methods-views.newton-method');
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
    
    public function calculateKutta(Request $request)
    {
        $x0 = $request->input('x0');
        $y0 = $request->input('y0');
        $h = $request->input('h');
        $n = $request->input('n');

        $result = $this->rungeKutta($x0, $y0, $h, $n);
        return view('methods-views.kutta-method', ['result' => $result]);
    }


    private function rungeKutta($x0, $y0, $h, $n)
    {
        $x = $x0;
        $y = $y0;
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            $k1 = $h * $this->f($x, $y);
            $k2 = $h * $this->f($x + 0.5 * $h, $y + 0.5 * $k1);
            $k3 = $h * $this->f($x + 0.5 * $h, $y + 0.5 * $k2);
            $k4 = $h * $this->f($x + $h, $y + $k3);
            $y = $y + (1 / 6) * ($k1 + 2 * $k2 + 2 * $k3 + $k4);
            $x = $x + $h;
            $result[] = ['x' => $x, 'y' => $y];
        }

        return $result;
    }
}
