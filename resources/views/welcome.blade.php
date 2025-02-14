<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metodos Numericos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8">Métodos Numéricos</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Euler Mejorado" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-2xl font-bold mb-2">Euler Mejorado</h2>
                    <p class="text-gray-700 mb-4">Descripción breve del método de Euler Mejorado.</p>
                    <a href="{{route('euler-method')}}" class="bg-blue-500 text-white px-4 py-2 rounded">Ver más</a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Runge Kutta" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-2xl font-bold mb-2">Runge Kutta</h2>
                    <p class="text-gray-700 mb-4">Descripción breve del método de Runge Kutta.</p>
                    <a href="{{ url('/runge-kutta') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Ver más</a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Newton Raphson" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-2xl font-bold mb-2">Newton Raphson</h2>
                    <p class="text-gray-700 mb-4">Descripción breve del método de Newton Raphson.</p>
                    <a href="{{ url('/newton-raphson') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Ver más</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>