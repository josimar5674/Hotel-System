<x-app-layout>

    <x-slot name="header">

     
    </x-slot>

    <div class="max-w-5xl mx-auto p-6">

        <div class="mb-8">

            <h1 class="text-3xl font-bold text-gray-800">
                💰 Nuevo Cierre Diario
            </h1>

            <p class="text-gray-500 mt-1">
                Verifique los ingresos del día y realice el cierre de caja.
            </p>

        </div>

        @if($errors->any())

        <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded mb-4">

            <ul class="list-disc pl-5">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('cierres.store') }}"
            method="POST">

            @csrf
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                  <div>
    <label class="text-xs text-gray-500 uppercase block mb-1">
        Fecha del Cierre
    </label>

    <input
        type="date"
        id="fecha"
        name="fecha"
        value="{{ old('fecha', \Carbon\Carbon::parse($fecha)->format('Y-m-d')) }}"
        class="border rounded-lg px-3 py-2 w-full">
</div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">
                            Facturas
                        </p>
                        <p class="font-bold text-indigo-600 text-xl">
                            {{ $cantidadFacturas }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">
                            Total Facturado
                        </p>
                        <p class="font-bold text-green-600">
                            L. {{ number_format($totalFacturado, 2) }}
                        </p>
                    </div>
                    @foreach($resumenPagos as $forma)
                    <div>
                        <p class="text-xs text-gray-500 uppercase">
                            {{ $forma['nombre'] }}
                        </p>
                        <p class="font-bold text-blue-600">
                            L. {{ number_format(
                    $forma['total'],
                    2
                ) }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            {{-- CIERRE DE CAJA --}}

            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">


               <h2 class="font-bold text-lg mb-4">

    Cierre de Caja

</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

@foreach($resumenPagos as $forma)

    @if($forma['total'] > 0)

    <div class="border rounded-xl p-4 bg-gray-50">

        <label class="block text-sm font-semibold mb-2">

            {{ $forma['nombre'] }} Facturado

        </label>

        <input
            type="text"
            readonly
            value="{{ number_format($forma['total'],2,'.','') }}"
            class="w-full border rounded p-2 bg-gray-100 mb-3">

        <label class="block text-sm font-semibold mb-2">

            {{ $forma['nombre'] }} Contado

        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="conteo[{{ $forma['id'] }}]"
            class="w-full border rounded p-2 conteo"
            data-facturado="{{ $forma['total'] }}">

    </div>

    @endif

@endforeach

</div>

        <div class="mt-6">

    <label class="block mb-2 font-medium">

        Diferencia Total

    </label>

    <div id="diferencia-card"
         class="rounded-xl p-6 bg-gray-100 text-center">

        <span id="diferencia-text"
              class="text-3xl font-bold">

            L. 0.00

        </span>

    </div>

</div>

                <div class="mt-4">

                    <label class="block mb-2 font-medium">

                        Observaciones

                    </label>

                    <textarea
                        name="observaciones"
                        rows="4"
                        class="w-full border rounded p-2"></textarea>

                </div>

            </div>

            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

                    Guardar Cierre

                </button>

                <a href="{{ route('cierres.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

    <script>
  document.addEventListener(
    'DOMContentLoaded',
    function() {

        const inputs =
            document.querySelectorAll(
                '.conteo'
            );

        const texto =
            document.getElementById(
                'diferencia-text'
            );

        const card =
            document.getElementById(
                'diferencia-card'
            );

        function recalcular() {

            let diferencia = 0;

            inputs.forEach(input => {

                let contado =
                    parseFloat(
                        input.value
                    ) || 0;

                let facturado =
                    parseFloat(
                        input.dataset.facturado
                    ) || 0;

                diferencia +=
                    contado -
                    facturado;

            });

            texto.innerHTML =
                'L. ' +
                diferencia.toFixed(2);

            texto.className =
                'text-3xl font-bold';

            card.className =
                'rounded-xl p-6 text-center';

            if(diferencia < 0){

                card.classList.add(
                    'bg-red-100'
                );

                texto.classList.add(
                    'text-red-600'
                );

            }
            else if(diferencia > 0){

                card.classList.add(
                    'bg-blue-100'
                );

                texto.classList.add(
                    'text-blue-600'
                );

            }
            else{

                card.classList.add(
                    'bg-green-100'
                );

                texto.classList.add(
                    'text-green-600'
                );

            }
        }

        inputs.forEach(input => {

            input.addEventListener(
                'input',
                recalcular
            );

        });

    }
);
    </script>

    <script>
document.getElementById('fecha')
.addEventListener('change', function() {

    window.location =
        "{{ route('cierres.create') }}?fecha=" +
        this.value;

});
</script>

</x-app-layout>