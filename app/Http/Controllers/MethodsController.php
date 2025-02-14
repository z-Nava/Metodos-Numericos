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

    public function calculateNewton(Request $request)
    {
        $x0 = $request->input('x0');
        $tol = $request->input('tol');
        $maxIter = $request->input('maxIter');

        $result = $this->newtonRaphson($x0, $tol, $maxIter);
        return view('methods-views.newton-method', ['result' => $result]);
    }

    public function newtonRaphson($x0, $tol, $maxIter)
    {
        $result = [];
        $x = $x0;
        for ($i = 0; $i < $maxIter; $i++) {
            $fx = $this->fN($x);
            $dfx = $this->df($x);
            if ($dfx == 0) {
                break; // Avoid division by zero
            }
            $x1 = $x - $fx / $dfx;
            $result[] = ['iteration' => $i, 'x' => $x1];
            if (abs($x1 - $x) < $tol) {
                break; // Convergence criterion
            }
            $x = $x1;
        }
        return $result;
    }

    private function fN($x)
    {
        // Define aquí tu función
        return $x * $x - 2; // Ejemplo: x^2 - 2
    }

    private function df($x)
    {
        // Define aquí la derivada de tu función
        return 2 * $x; // Ejemplo: derivada de x^2 - 2 es 2x
    }

    private function f($x, $y)
    {
        // Define aquí tu función diferencial
        return $x + $y;
    }
}
