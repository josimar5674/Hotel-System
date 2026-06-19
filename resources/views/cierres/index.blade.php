<x-app-layout>


<x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Cierres Diarios

        </h2>

    </x-slot>
<div class="max-w-7xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">

        <div>

          <h1 class="text-3xl font-bold text-gray-800">
    📋 Cierres Diarios
</h1>

<p class="text-gray-500 mt-1">
    Control y auditoría de cierres de caja.
</p>
        </div>
<a href="{{ route('cierres.create') }}"
   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl shadow-lg font-medium">
    + Nuevo Cierre
</a>

    </div>

    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">

            {{ session('error') }}

        </div>

    @endif

<div class="bg-white rounded-xl shadow-lg overflow-hidden">

<table class="w-full">
    
            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left p-3">
                        Fecha
                    </th>

                    <th class="text-left p-3">
                        Usuario
                    </th>

                    <th class="text-center p-3">
                        Facturas
                    </th>

                    <th class="text-right p-3">
                        Total Facturado
                    </th>

                    <th class="text-right p-3">
                        Diferencia
                    </th>

                    <th class="text-center p-3">
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($cierres as $cierre)

<tr class="border-t hover:bg-gray-50 transition">

                        <td class="p-3">

                            {{ $cierre->fecha->format('d/m/Y') }}

                        </td>

                        <td class="p-3">

                            {{ $cierre->usuario->name }}

                        </td>

                        <td class="text-center p-3">

                            {{ $cierre->cantidad_facturas }}

                        </td>

                        <td class="text-right p-3">

                            L. {{ number_format(
                                $cierre->total_facturado,
                                2
                            ) }}

                        </td>

                        <td class="text-right p-3">

                            @if($cierre->diferencia == 0)

                                <span class="text-green-600 font-semibold">

                                    L. 0.00

                                </span>

                            @elseif($cierre->diferencia < 0)

                                <span class="text-red-600 font-semibold">

                                    L. {{ number_format(
                                        $cierre->diferencia,
                                        2
                                    ) }}

                                </span>

                            @else

                                <span class="text-blue-600 font-semibold">

                                    L. {{ number_format(
                                        $cierre->diferencia,
                                        2
                                    ) }}

                                </span>

                            @endif

                        </td>

                        <td class="text-center p-3">

                            <a href="{{ route(
                                'cierres.show',
                                $cierre
                            ) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow">

                                Ver

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center p-6 text-gray-500">

                            No existen cierres registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">

        {{ $cierres->links() }}

    </div>

</div>

</x-app-layout>