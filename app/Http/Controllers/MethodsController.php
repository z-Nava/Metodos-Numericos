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
        // Convertimos los valores a float para evitar problemas con la evaluación
        $x0 = floatval($request->input('x0'));
        $y0 = floatval($request->input('y0'));
        $h = floatval($request->input('h'));
        $n = intval($request->input('n')); // Aseguramos que es un número entero
        $equation = $request->input('equation');

        // Llamamos al método de Euler Mejorado
        $result = $this->eulerMejorado($x0, $y0, $h, $n, $equation);

        return view('methods-views.euler-method', ['result' => $result]);
    }

    private function eulerMejorado($x0, $y0, $h, $n, $equation)
{
    $x = $x0;
    $y = $y0;
    $result = [];

    // Reemplazamos operadores incorrectos antes de evaluar
    $equation = str_replace('^', '**', $equation);

    for ($i = 0; $i < $n; $i++) {
        // Reemplazamos 'x' y 'y' en la ecuación con sus valores actuales
        $equationEvaluable = str_replace(['x', 'y'], ['$x', '$y'], $equation);

        // Función anónima segura para evaluar la ecuación
        $f = function ($x, $y) use ($equationEvaluable) {
            return eval("return $equationEvaluable;");
        };

        // Método de Euler Mejorado
        $k1 = $h * $f($x, $y);
        $k2 = $h * $f($x + $h, $y + $k1);
        $y = $y + 0.5 * ($k1 + $k2);
        $x = $x + $h;

        // Guardamos los resultados
        $result[] = ['x' => round($x, 6), 'y' => round($y, 6)];
    }

    return $result;
}





    

    
public function calculateKutta(Request $request)
{
    $x0 = floatval($request->input('x0'));
    $y0 = floatval($request->input('y0'));
    $h = floatval($request->input('h'));
    $n = intval($request->input('n'));
    $equation = $request->input('equation');  

    $result = $this->rungeKutta($x0, $y0, $h, $n, $equation);

    return view('methods-views.kutta-method', ['result' => $result]);
}

private function rungeKutta($x0, $y0, $h, $n, $equation)
{
    $x = $x0;
    $y = $y0;
    $result = [];

    // Convertimos ^ en ** para la potencia en PHP
    $equation = str_replace('^', '**', $equation);

    for ($i = 0; $i < $n; $i++) {
        // Reemplazamos 'x' y 'y' por variables PHP
        $equationEvaluable = str_replace(['x', 'y'], ['$x', '$y'], $equation);

        $f = function ($x, $y) use ($equationEvaluable) {
            return eval("return $equationEvaluable;");
        };

        // Runge-Kutta de cuarto orden
        $k1 = $h * $f($x, $y);
        $k2 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k1);
        $k3 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k2);
        $k4 = $h * $f($x + $h, $y + $k3);

        $y = $y + (1 / 6) * ($k1 + 2 * $k2 + 2 * $k3 + $k4);
        $x = $x + $h;

        // Redondeamos para mayor precisión
        $result[] = ['x' => round($x, 6), 'y' => round($y, 6)];
    }

    return $result;
}


public function calculateNewton(Request $request)
{
    $x0 = floatval($request->input('x0'));
    $tol = floatval($request->input('tol'));
    $maxIter = intval($request->input('maxIter'));
    $equation = $request->input('equation'); 

    $result = $this->newtonRaphson($x0, $tol, $maxIter, $equation);
    return view('methods-views.newton-method', ['result' => $result]);
}

private function newtonRaphson($x0, $tol, $maxIter, $equation)
{
    $result = [];
    $x = $x0;

    // Convertimos ^ en ** para la potencia en PHP
    $equation = str_replace('^', '**', $equation);

    for ($i = 0; $i < $maxIter; $i++) {
        $fx = $this->evaluateFunction($equation, $x);
        $dfx = $this->evaluateDerivative($equation, $x);

        if ($dfx == 0) {
            break; // Evita división por cero
        }

        $x1 = $x - $fx / $dfx;
        $result[] = ['iteration' => $i, 'x' => round($x1, 6)];

        if (abs($x1 - $x) < $tol) {
            break; // Criterio de convergencia
        }

        $x = $x1;
    }

    return $result;
}

private function evaluateFunction($equation, $x)
{
    $equationEvaluable = str_replace('x', '$x', $equation);
    return eval("return $equationEvaluable;");
}

private function evaluateDerivative($equation, $x)
{
    // Aproximación numérica de la derivada usando diferencia central
    $h = 1e-6;
    $df = ($this->evaluateFunction($equation, $x + $h) - $this->evaluateFunction($equation, $x - $h)) / (2 * $h);
    return $df;
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
