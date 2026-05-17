<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Clientes

        </h2>

    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-4">


            <!-- TOP BAR -->

            <div class="flex justify-between items-center mb-4 gap-4">

                <a href="{{ route('clientes.create') }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded">

                    Nuevo Cliente

                </a>


                <input type="text"
                       id="buscarClientes"
                       placeholder="Buscar cliente..."
                       class="border rounded p-2 w-72">

            </div>


            <!-- TABLA -->

            <table class="w-full border">

                <thead>

                    <tr class="bg-gray-200">

                        <th class="p-3 text-center">
                            Nombre
                        </th>

                        <th class="p-3 text-center">
                            Identidad
                        </th>

                        <th class="p-3 text-center">
                            RTN
                        </th>

                        <th class="p-3 text-center">
                            Teléfono
                        </th>

                        <th class="p-3 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody id="tablaClientes">

                    @forelse($clientes as $cliente)

                        <tr class="border-t">

                            <td class="p-3 text-center align-middle">

                                {{ $cliente->nombre }}

                            </td>

                            <td class="p-3 text-center align-middle">

                                {{ $cliente->identidad }}

                            </td>

                            <td class="p-3 text-center align-middle">

                                {{ $cliente->rtn }}

                            </td>

                            <td class="p-3 text-center align-middle">

                                {{ $cliente->telefono }}

                            </td>

                            <td class="p-3 text-center align-middle">

                                <div class="flex justify-center">

                                    <a href="{{ route(
                                        'clientes.edit',
                                        $cliente->id
                                    ) }}"

                                       class="bg-yellow-500 text-white px-3 py-1 rounded min-w-[90px]">

                                        Editar

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="p-4 text-center">

                                No hay clientes registrados

                            </td>

                        </tr>

                    @endforelse

                </tbody>

                <div class="mt-4">

    {{ $clientes->links() }}

</div>
            </table>

        </div>

    </div>


    <script>

        // BUSCADOR DINÁMICO

        document.getElementById('buscarClientes')
        .addEventListener('keyup', function() {

            const filtro = this.value.toLowerCase();

            const filas = document.querySelectorAll(
                '#tablaClientes tr'
            );

            filas.forEach(fila => {

                const texto = fila.innerText
                    .toLowerCase();

                if(texto.includes(filtro)) {

                    fila.style.display = '';

                }

                else {

                    fila.style.display = 'none';

                }

            });

        });

    </script>

</x-app-layout>