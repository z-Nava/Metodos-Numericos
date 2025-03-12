<!DOCTYPE html>
<html>
<head>
    <title>Newton Raphson</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Método de Newton-Raphson</h1>
            <a href="{{ route('welcome') }}" class="bg-green-500 text-white px-4 py-2 rounded transition duration-300 hover:bg-green-600">
                Ir a Inicio
            </a>
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Ingrese los datos</h2>
                <form action="{{ route('calculate-newton') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="equation" class="block text-gray-700">Ecuación (f(x)):</label>
                        <textarea name="equation" id="equation" rows="2" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                            oninput="validateEquation()"></textarea>
                        <small class="text-gray-500">Ejemplo: x^3 - x - 2</small>
                        <p id="error-message" class="text-red-500 hidden">❌ No puedes ingresar la variable "y". Usa solo "x".</p>
                    </div>
                    
                    <script>
                        function validateEquation() {
                            let textarea = document.getElementById("equation");
                            let errorMessage = document.getElementById("error-message");
                        
                            // Eliminar cualquier aparición de "y"
                            let newValue = textarea.value.replace(/y/g, '');
                            
                            if (textarea.value !== newValue) {
                                errorMessage.classList.remove("hidden"); // Mostrar mensaje de error
                            } else {
                                errorMessage.classList.add("hidden"); // Ocultar mensaje de error
                            }
                        
                            textarea.value = newValue; // Asignar el nuevo valor sin "y"
                        }
                        </script>
                    
                
                    <!-- Vista previa de la ecuación con MathJax -->
                    <p class="mt-2 text-gray-700">Vista previa de la ecuación:</p>
                    <div id="equation-preview" class="text-xl font-semibold text-indigo-700"></div>
                
                    <div class="mb-4">
                        <label for="x0" class="block text-gray-700">Valor inicial (x₀):</label>
                        <input type="number" step="any" name="x0" id="x0" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>  
                
                    <div class="mb-4">
                        <label for="precision" class="block text-gray-700">Número de decimales:</label>
                        <input type="number" name="precision" id="precision" value="0" min="0" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                
                    <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600">
                        Calcular
                    </button>
                </form>
            </div>
            <!-- Resultados -->
            @if(isset($result))
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Resultados del Método de Newton-Raphson</h2>
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
        <!-- MathJax para la vista previa de ecuaciones -->
        <script type="text/javascript" async
            src="https://polyfill.io/v3/polyfill.min.js?features=es6">
        </script>
        <script type="text/javascript" async
            src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
        </script>
        
        <!-- Script para actualizar la vista previa de la ecuación -->
        <script>
            function updateEquation() {
                let equationInput = document.getElementById("equation").value;
                let equationPreview = document.getElementById("equation-preview");
        
                // Reemplazamos ^ por { } en MathJax para exponentes correctos
                let formattedEquation = equationInput
                    .replace(/\^(\d+)/g, '^{\$1}')  // x^2 → x^{2}
                    .replace(/\*/g, '\\cdot ') // * → ⋅ para multiplicación
        
                equationPreview.innerHTML = `\\( ${formattedEquation} \\)`;
                MathJax.typesetPromise();
            }
        </script>
        
        
    </body>
</html>