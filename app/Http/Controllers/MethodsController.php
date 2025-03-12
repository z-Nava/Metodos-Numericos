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
    try {
        // Validar los datos ingresados
        $request->validate([
            'equation' => 'required|string',
            'x0' => 'required|numeric',
            'y0' => 'required|numeric',
            'h' => 'required|numeric|gt:0', // h debe ser mayor que 0
            'n' => 'required|integer|min:1', // n debe ser al menos 1
            'decimales' => 'required|integer|min:0|max:10' // Limita el número de decimales entre 0 y 10
        ]);

        $x0 = floatval($request->input('x0'));
        $y0 = floatval($request->input('y0'));
        $h = floatval($request->input('h'));
        $n = intval($request->input('n'));
        $decimales = intval($request->input('decimales'));
        $equation = $request->input('equation');

        // Llamamos a la función Euler Mejorado
        $result = $this->eulerMejorado($x0, $y0, $h, $n, $equation, $decimales);

        return view('methods-views.euler-method', ['result' => $result]);

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Error en el cálculo: ' . $e->getMessage()]);
    }
}

private function eulerMejorado($x0, $y0, $h, $n, $equation, $decimales)
{
    try {
        $x = $x0;
        $y = $y0;
        $result = [];

        // Convertimos "^" en "**" (para exponenciación en PHP)
        $equation = str_replace('^', '**', $equation);

        // Asegurar que los coeficientes numéricos tengan "*"
        // Ejemplo: "2xy" → "2*x*y", "3x" → "3*x", "4y" → "4*y"
        $equation = preg_replace('/(\d)([xy])/', '$1*$2', $equation);
        $equation = preg_replace('/([xy])([xy])/', '$1*$2', $equation); // Maneja "xy" → "x*y"

        // Soporte para funciones matemáticas como sin(x), exp(x), log(x)
        $equation = preg_replace_callback('/(sin|cos|tan|exp|log|sqrt)\((.*?)\)/', function ($matches) {
            return $matches[1] . '($2)';
        }, $equation);

        // Convertir "x" y "y" en variables seguras
        $equation = str_replace(['x', 'y'], ['$x', '$y'], $equation);

        // Definir la función matemática segura
        $f = function ($x, $y) use ($equation) {
            try {
                // Asegurar formato correcto de números flotantes
                $x = (strpos($x, '.') === 0) ? '0' . $x : $x;
                $y = (strpos($y, '.') === 0) ? '0' . $y : $y;

                // Reemplazar variables en la ecuación
                $expr = str_replace(['$x', '$y'], [$x, $y], $equation);

                // Asegurar el formato correcto de números decimales (0.x en vez de .x)
                $expr = preg_replace('/\b\.(\d+)/', '0.$1', $expr);

                return eval("return (" . $expr . ");");
            } catch (\Throwable $e) {
                throw new \Exception("Error en la evaluación de la ecuación: " . $e->getMessage());
            }
        };

        // Iterar N veces
        for ($i = 0; $i < $n; $i++) {
            // Evaluación de la ecuación
            $fx = $f($x, $y);

            // Método de Euler Mejorado
            $k1 = $h * $fx;
            $tempY = $y + $k1;
            $k2 = $h * $f($x + $h, $tempY);
            $y = $y + 0.5 * ($k1 + $k2);
            $x = $x + $h;

            // Guardar resultados con valores redondeados al final
            $result[] = [
                'x' => round($x, $decimales),
                'y' => round($y, $decimales)
            ];
        }

        return $result;
    } catch (\Throwable $e) {
        throw new \Exception("Error en el método Euler Mejorado: " . $e->getMessage());
    }
}




public function calculateKutta(Request $request)
{
    try {
        // Validar que los valores ingresados sean correctos
        $request->validate([
            'equation' => 'required|string',
            'x0' => 'required|numeric',
            'y0' => 'required|numeric',
            'h' => 'required|numeric|gt:0',  // h debe ser mayor que 0
            'n' => 'required|integer|min:1'  // n debe ser al menos 1
        ], [
            'h.gt' => 'El tamaño del paso (h) debe ser mayor que 0.',
            'n.min' => 'El número de pasos (n) debe ser al menos 1.'
        ]);

        $x0 = floatval($request->input('x0'));
        $y0 = floatval($request->input('y0'));
        $h = floatval($request->input('h'));
        $n = intval($request->input('n'));
        $equation = $request->input('equation');

        // Llamar a la función Runge-Kutta
        $result = $this->rungeKutta($x0, $y0, $h, $n, $equation);

        return view('methods-views.kutta-method', ['result' => $result]);

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Error en el cálculo: ' . $e->getMessage()]);
    }
}

private function rungeKutta($x0, $y0, $h, $n, $equation)
{
    try {
        $x = $x0;
        $y = $y0;
        $result = [];

        // Convertimos "^" en "**" para potencias en PHP
        $equation = str_replace('^', '**', $equation);

        // Asegurar que los coeficientes numéricos tengan "*"
        $equation = preg_replace('/(\d)([xy])/', '$1*$2', $equation);
        $equation = preg_replace('/([xy])([xy])/', '$1*$2', $equation); // Maneja "xy" → "x*y"

        // Soporte para funciones matemáticas como sin(x), exp(x), log(x), sqrt(x)
        $equation = preg_replace_callback('/(sin|cos|tan|exp|log|sqrt)\((.*?)\)/', function ($matches) {
            return $matches[1] . '($2)';
        }, $equation);

        // Convertir "x" y "y" en variables seguras
        $equation = str_replace(['x', 'y'], ['$x', '$y'], $equation);

        // Definir la función matemática segura
        $f = function ($x, $y) use ($equation) {
            try {
                // Asegurar formato correcto de números flotantes
                $x = (strpos($x, '.') === 0) ? '0' . $x : $x;
                $y = (strpos($y, '.') === 0) ? '0' . $y : $y;

                // Reemplazar variables en la ecuación
                $expr = str_replace(['$x', '$y'], [$x, $y], $equation);

                // Asegurar el formato correcto de números decimales (0.x en vez de .x)
                $expr = preg_replace('/\b\.(\d+)/', '0.$1', $expr);

                return eval("return (" . $expr . ");");
            } catch (\Throwable $e) {
                throw new \Exception("Error en la evaluación de la ecuación: " . $e->getMessage());
            }
        };

        // Iterar N veces (Runge-Kutta de 4to orden)
        for ($i = 0; $i < $n; $i++) {
            // Calcular valores de k1, k2, k3, k4
            $k1 = $h * $f($x, $y);
            $k2 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k1);
            $k3 = $h * $f($x + 0.5 * $h, $y + 0.5 * $k2);
            $k4 = $h * $f($x + $h, $y + $k3);

            // Aplicar la fórmula de Runge-Kutta
            $y = $y + (1 / 6) * ($k1 + 2 * $k2 + 2 * $k3 + $k4);
            $x = $x + $h;

            // Guardar resultados con valores redondeados al final
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
    } catch (\Throwable $e) {
        throw new \Exception("Error en el método Runge-Kutta: " . $e->getMessage());
    }
}

public function calculateNewton(Request $request)
{
    try {
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

        // Llamar a la función de Newton-Raphson
        $result = $this->newtonRaphson($x0, $precision, $equation);

        return view('methods-views.newton-method', ['result' => $result]);

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Error en el cálculo: ' . $e->getMessage()]);
    }
}

private function newtonRaphson($x0, $precision, $equation)
{
    try {
        $result = [];
        $x = $x0;
        $maxIter = 100;  // Máximo de iteraciones para evitar bucles infinitos
        $tol = pow(10, -$precision); // Definir tolerancia según la precisión

        // Convertimos "^" en "**" para la potencia en PHP
        $equation = str_replace('^', '**', $equation);

        for ($i = 0; $i < $maxIter; $i++) {
            $fx = $this->evaluateFunction($equation, $x);
            $dfx = $this->evaluateDerivative($equation, $x);

            if ($dfx == 0) {
                throw new \Exception("La derivada es cero en x = $x, el método no puede continuar.");
            }

            $x1 = $x - $fx / $dfx;

            // Guardamos los resultados
            $result[] = [
                'iteration' => $i + 1,
                'x' => round($x1, $precision),
                'f(x)' => round($fx, $precision),
                'f\'(x)' => round($dfx, $precision)
            ];

            if (abs($x1 - $x) < $tol) {
                break; // Criterio de convergencia
            }

            $x = $x1;
        }

        return $result;
    } catch (\Throwable $e) {
        throw new \Exception("Error en el método Newton-Raphson: " . $e->getMessage());
    }
}

private function evaluateFunction($equation, $x)
{
    try {
        // Convertir ^ en ** y agregar multiplicaciones implícitas
        $equation = str_replace('^', '**', $equation);
        $equation = preg_replace('/(\d)(x)/', '$1*$2', $equation);

        // Soporte para funciones matemáticas como sin(x), exp(x), log(x)
        $equation = preg_replace_callback('/(sin|cos|tan|exp|log|sqrt)\((.*?)\)/', function ($matches) {
            return $matches[1] . '($2)';
        }, $equation);

        // Convertir "x" en variable segura
        $equation = str_replace('x', '$x', $equation);

        // Evaluar la función
        return eval("return (function(\$x){ return $equation; })($x);");

    } catch (\Throwable $e) {
        throw new \Exception("Error al evaluar la ecuación: " . $e->getMessage());
    }
}

private function evaluateDerivative($equation, $x)
{
    try {
        $h = 1e-6; // Pequeño incremento para aproximación numérica
        return ($this->evaluateFunction($equation, $x + $h) - $this->evaluateFunction($equation, $x - $h)) / (2 * $h);
    } catch (\Throwable $e) {
        throw new \Exception("Error al calcular la derivada: " . $e->getMessage());
    }
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
