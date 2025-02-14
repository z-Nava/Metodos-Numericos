<!DOCTYPE html>
<html>
<head>
    <title>Newton Raphson</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Método de Newton Raphson</h1>

        <!-- Formulario -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Ingrese los datos</h2>
            <form action="{{route('calculate-newton')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="x0" class="block text-gray-700">Valor inicial:</label>
                    <input type="number" step="any" name="x0" id="x0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="tol" class="block text-gray-700">Tolerancia:</label>
                    <input type="number" step="any" name="tol" id="tol" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="maxIter" class="block text-gray-700">Iteraciones:</label>
                    <input type="number" name="maxIter" id="maxIter" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600">Calcular</button>
            </form>
        </div>

            <!-- Resultados -->
            @if(isset($result))
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Resultados del Método de Newton Raphson</h2>
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="w-1/2 px-4 py-2">Iteración</th>
                            <th class="w-1/2 px-4 py-2">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $iteration => $value)
                            <tr class="bg-gray-100 border-b">
                                <td class="text-center px-4 py-2">{{ $iteration }}</td>
                                <td class="text-center px-4 py-2">{{ $value['x'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif    
        </div>
    </body>
</html>