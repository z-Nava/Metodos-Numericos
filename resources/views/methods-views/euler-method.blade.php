<!DOCTYPE html>
<html>
<head>
    <title>Euler Mejorado</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Método de Euler Mejorado</h1>
        
        <!-- Formulario -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Ingrese los datos</h2>
            <form action="{{route('calculate-euler')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="decimales" class="block text-gray-700">Decimales</label>
                    <input type="number" step="any" name="decimales" id="decimales" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="equation" class="block text-gray-700">Ecuación (f(x, y)):</label>
                    <textarea name="equation" id="equation" rows="2" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        oninput="updateEquation()"></textarea>
                    <small class="text-gray-500">Ejemplo: x^2 - y</small>
                </div>
                
                <!-- Aquí se mostrará la ecuación en formato MathJax -->
                <p class="mt-2 text-gray-700">Vista previa de la ecuación:</p>
                <div id="equation-preview" class="text-xl font-semibold text-indigo-700"></div>
                
                <div class="mb-4">
                    <label for="x0" class="block text-gray-700">x0:</label>
                    <input type="number" step="any" name="x0" id="x0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="y0" class="block text-gray-700">y0:</label>
                    <input type="number" step="any" name="y0" id="y0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="h" class="block text-gray-700">h (tamaño del paso):</label>
                    <input type="number" step="any" name="h" id="h" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="n" class="block text-gray-700">n (número de pasos):</label>
                    <input type="number" name="n" id="n" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600">Calcular</button>
            </form>
        </div>
        @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <strong>Errores en el formulario:</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <!-- Resultados -->
        @if(isset($result))
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Resultados del Método de Euler Mejorado</h2>
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="w-1/2 px-4 py-2">x</th>
                            <th class="w-1/2 px-4 py-2">y</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $point)
                            <tr class="bg-gray-100 border-b">
                                <td class="text-center px-4 py-2">{{ $point['x'] }}</td>
                                <td class="text-center px-4 py-2">{{ $point['y'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script type="text/javascript" async
   src="https://polyfill.io/v3/polyfill.min.js?features=es6">
</script>
<script type="text/javascript" async
   src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
</script>
<script>
    function updateEquation() {
        let equationInput = document.getElementById("equation").value;
        let equationPreview = document.getElementById("equation-preview");

        // Reemplazamos los operadores correctamente para MathJax
        let formattedEquation = equationInput
            .replace(/\^(\d+)/g, '^{\$1}') // Corrige exponentes con números (x^2 → x^{2})
            .replace(/\*/g, '\\cdot ') // Cambia * por ⋅ para multiplicación

        // Mostrar en MathJax
        equationPreview.innerHTML = `\\( ${formattedEquation} \\)`;
        MathJax.typesetPromise(); // Renderiza MathJax
    }
</script>


</body>
</html>
