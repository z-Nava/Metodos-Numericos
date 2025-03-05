<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Métodos Numéricos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center text-sm text-gray-500 mb-4">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>

        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Métodos Numéricos</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card: Euler Mejorado -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                <h2 class="text-2xl font-bold mb-2 text-center">Euler Mejorado</h2>
                <p class="text-gray-700 text-center mb-4">Descripción breve del método de Euler Mejorado.</p>
                <a href="{{ route('euler-method') }}" class="bg-blue-500 text-white px-4 py-2 rounded transition duration-300 hover:bg-blue-600">
                    Ver más
                </a>
            </div>

            <!-- Card: Runge Kutta -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                <h2 class="text-2xl font-bold mb-2 text-center">Runge Kutta</h2>
                <p class="text-gray-700 text-center mb-4">Descripción breve del método de Runge Kutta.</p>
                <a href="{{ route('kutta-method') }}" class="bg-blue-500 text-white px-4 py-2 rounded transition duration-300 hover:bg-blue-600">
                    Ver más
                </a>
            </div>

            <!-- Card: Newton Raphson -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                <h2 class="text-2xl font-bold mb-2 text-center">Newton Raphson</h2>
                <p class="text-gray-700 text-center mb-4">Descripción breve del método de Newton Raphson.</p>
                <a href="{{ route('newton-method') }}" class="bg-blue-500 text-white px-4 py-2 rounded transition duration-300 hover:bg-blue-600">
                    Ver más
                </a>
            </div>
        </div>
    </div>
</body>
</html>
    