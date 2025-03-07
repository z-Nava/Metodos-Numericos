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
    // Validar los datos ingresados
    $request->validate([
        'equation' => 'required|string',
        'x0' => 'required|numeric',
        'y0' => 'required|numeric',
        'h' => 'required|numeric|gt:0', // h debe ser mayor que 0
        'n' => 'required|numeric|min:1', // n debe ser al menos 1
        'decimales' => 'required|integer|min:0|max:10' // Limita el número de decimales entre 0 y 10
    ]);

    $x0 = floatval($request->input('x0'));
    $y0 = floatval($request->input('y0'));
    $h = floatval($request->input('h'));
    $n = floatval($request->input('n'));
    $decimales = intval($request->input('decimales'));
    $equation = $request->input('equation');

    $result = $this->eulerMejorado($x0, $y0, $h, $n, $equation, $decimales);

    return view('methods-views.euler-method', ['result' => $result]);
}

private function eulerMejorado($x0, $y0, $h, $n, $equation, $decimales)
{
    $x = $x0;
    $y = $y0;
    $result = [];

    // 1. Convertimos ^ en ** (para exponentes en PHP)
    $equation = str_replace('^', '**', $equation);

    // 2. Aseguramos que los coeficientes numéricos de "x" y "y" tengan un "*"
    // Ejemplo: "3x" se convierte en "3*x", "2y" en "2*y"
    $equation = preg_replace('/(\d)([xy])/', '$1*$2', $equation);

    // 3. Reemplazamos "x" y "y" por "$x" y "$y"
    $equation = str_replace(['x', 'y'], ['$x', '$y'], $equation);

    // 4. Creamos la función evaluable sin errores
    $f = function ($x, $y) use ($equation) {
        return eval("return (" . str_replace(['$x', '$y'], [$x, $y], $equation) . ");");
    };

    // Número de iteraciones correctas
    $iterations = floor($n / $h);

    for ($i = 0; $i < $iterations; $i++) {
        // Evaluación de la ecuación en la iteración actual
        $fx = $f($x, $y);

        // Método de Euler Mejorado
        $k1 = round($h * $fx, $decimales);
        $tempY = round($y + $k1, $decimales);
        $k2 = round($h * $f($x + $h, $tempY), $decimales);
        $y = round($y + 0.5 * ($k1 + $k2), $decimales);
        $x = round($x + $h, $decimales);

        // Guardar resultados con valores numéricos correctos
        $result[] = [
            'x' => $x,
            'y' => $y
        ];
    }

    return $result;
}


    public function calculateKutta(Request $request)
    {
        // Validar que los valores ingresados sean correctos
        $request->validate([
            'equation' => 'required|string',
            'x0' => 'required|numeric',
            'y0' => 'required|numeric',
            'h' => 'required|numeric|gt:0',  // h debe ser mayor que 0
            'n' => 'required|numeric|min:1'  // n debe ser al menos 1
        ], [
            'h.gt' => 'El tamaño del paso (h) debe ser mayor que 0.',
            'n.min' => 'El número de pasos (n) debe ser al menos 1.'
        ]);

        $x0 = floatval($request->input('x0'));
        $y0 = floatval($request->input('y0'));
        $h = floatval($request->input('h'));
        $n = floatval($request->input('n'));
        $equation = $request->input('equation');  

        $result = $this->rungeKutta($x0, $y0, $h, $n, $equation);

        return view('methods-views.kutta-method', ['result' => $result]);
    }


        private function rungeKutta($x0, $y0, $h, $n, $equation)
    {
        $x = $x0;
        $y = $y0;
        $result = [];

        // 1. Convertir ^ en ** para potencias en PHP
        $equation = str_replace('^', '**', $equation);

        // 2. Asegurar que los coeficientes numéricos de "x" y "y" tengan un "*"
        $equation = preg_replace('/(\d)([xy])/', '$1*$2', $equation);

        // 3. Reemplazar "x" y "y" por "$x" y "$y"
        $equation = str_replace(['x', 'y'], ['$x', '$y'], $equation);

        // 4. Crear la función de evaluación
        $f = function ($x, $y) use ($equation) {
            return eval("return $equation;");
        };

        for ($i = 0; $i < $n; $i++) {
            // Calcular valores de k1, k2, k3, k4
            $k1 = $h * $f($x, $y);
            $k2 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k1);
            $k3 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k2);
            $k4 = $h * $f($x + $h, $y + $k3);

            $y = $y + (1 / 6) * ($k1 + 2 * $k2 + 2 * $k3 + $k4);
            $x = $x + $h;

            // Guardamos los resultados en la lista
            $result[] = [
                'x' => round($x, 6),
                'y' => round($y, 6),
                'k1' => round($k1, 6),
                'k2' => round($k2, 6),
                'k3' => round($k3, 6),
                'k4' => round($k4, 6)
            ];
        }

        return $result;
    }
    
    public function calculateNewton(Request $request)
    {
        // Validaciones para evitar errores en el cálculo
        $request->validate([
            'equation' => 'required|string',
            'x0' => 'required|numeric',
            'precision' => 'required|integer|min:1|max:10', // Precisión entre 1 y 10
        ], [
            'precision.min' => 'El número de decimales debe ser al menos 1.',
            'precision.max' => 'El número de decimales no puede ser mayor a 10.'
        ]);

        $x0 = floatval($request->input('x0'));
        $precision = intval($request->input('precision'));
        $equation = $request->input('equation');

        $result = $this->newtonRaphson($x0, $precision, $equation);

        return view('methods-views.newton-method', ['result' => $result]);
    }

    private function newtonRaphson($x0, $precision, $equation)
    {
        $result = [];
        $x = $x0;
        $maxIter = 100;  // Máximo de iteraciones para evitar bucles infinitos
        $tol = pow(10, -$precision); // Definir tolerancia según la precisión

        // Convertimos ^ en ** para la potencia en PHP
        $equation = str_replace('^', '**', $equation);

        for ($i = 0; $i < $maxIter; $i++) {
            $fx = $this->evaluateFunction($equation, $x);
            $dfx = $this->evaluateDerivative($equation, $x);

            if ($dfx == 0) {
                return ['error' => 'La derivada es cero, el método no puede continuar.'];
            }

            $x1 = $x - $fx / $dfx;

            // Guardamos los resultados
            $result[] = [
                'iteration' => $i,
                'x' => number_format($x1, $precision, '.', '')
            ];

            if (abs($x1 - $x) < $tol) {
                break; // Criterio de convergencia
            }

            $x = $x1;
        }

        return $result;
    }

    private function evaluateFunction($equation, $x)
    {
        $equation = str_replace('^', '**', $equation);

        $equation = preg_replace('/(\d)(x)/', '$1*$2', $equation);

        $equation = str_replace('x', '$x', $equation);
        
        return eval("return (function(\$x){ return $equation; })($x);");
    }

    private function evaluateDerivative($equation, $x)
    {
        $h = 1e-6;
        return ($this->evaluateFunction($equation, $x + $h) - $this->evaluateFunction($equation, $x - $h)) / (2 * $h);
    }

    private function fN($x)
    {
        return $x * $x - 2; 
    }
    
    private function df($x)
    {       
        return 2 * $x; 
    }

    private function f($x, $y)
    {
        return $x + $y;
    }
}
