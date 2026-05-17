<x-app-layout>



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Habitaciones
        </h2>
    </x-slot>

    <div class="p-6">

    


        <a href="{{ route('habitaciones.create') }}"
   class="bg-blue-500 text-white px-4 py-2 rounded">

    Nueva Habitación

</a>
        <table class="w-full mt-4 border">

            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Número</th>
                    <th class="p-2">Tipo</th>
                    <th class="p-2">Precio</th>
                    <th class="p-2">Estado</th>
                    <th class="p-2">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($habitaciones as $habitacion)
                    <tr class="border-t">
                        <td class="p-2">{{ $habitacion->numero }}</td>
                        <td class="p-2">{{ $habitacion->tipo }}</td>
                        <td class="p-2">L {{ $habitacion->precio }}</td>
                        <td class="p-2">{{ $habitacion->estado }}</td>
                        <td class="p-2">

    <a href="{{ route('habitaciones.edit', $habitacion->id) }}"
       class="bg-yellow-500 text-white px-3 py-1 rounded">

        Editar

    </a>

</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</x-app-layout>