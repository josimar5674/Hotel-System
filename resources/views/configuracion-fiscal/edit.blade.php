<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Configuración Fiscal
        </h2>
    </x-slot>

    <div class="p-6">

        @if(session('success'))

        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>

        @endif

        <div class="bg-white shadow rounded-lg p-6">

            <form method="POST"
                action="{{ route('configuracion-fiscal.update') }}">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Nombre Negocio</label>

                        <input type="text"
                            name="nombre_negocio"
                            value="{{ $config->nombre_negocio }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Razón Social</label>

                        <input type="text"
                            name="razon_social"
                            value="{{ $config->razon_social }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>RTN</label>

                        <input type="text"
                            name="rtn"
                            value="{{ $config->rtn }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Teléfono</label>

                        <input type="text"
                            name="telefono"
                            value="{{ $config->telefono }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Correo</label>

                        <input type="text"
                            name="correo"
                            value="{{ $config->correo }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Dirección</label>

                        <input type="text"
                            name="direccion"
                            value="{{ $config->direccion }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>CAI</label>

                        <input type="text"
                            name="cai"
                            value="{{ $config->cai }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Serie</label>

                        <input type="text"
                            name="serie"
                            value="{{ $config->serie }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Factura Inicio</label>

                        <input type="date"
                            name="factura_inicio"
                            value="{{ $config->factura_inicio }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Factura Fin</label>

                        <input type="date"
                            name="factura_fin"
                            value="{{ $config->factura_fin }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Siguiente Número</label>

                        <input type="text"
                            name="siguiente_numero"
                            value="{{ $config->siguiente_numero }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Fecha Límite</label>

                        <input type="date"
                            name="fecha_limite"
                            value="{{ $config->fecha_limite }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Impuesto 15%</label>

                        <input type="number"
                            step="0.01"
                            name="impuesto_15"
                            value="{{ $config->impuesto_15 }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Impuesto 18%</label>

                        <input type="number"
                            step="0.01"
                            name="impuesto_18"
                            value="{{ $config->impuesto_18 }}"
                            class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label>Impuesto Turismo</label>

                        <input type="number"
                            step="0.01"
                            name="impuesto_turismo"
                            value="{{ $config->impuesto_turismo }}"
                            class="w-full border rounded p-2">
                    </div>

                </div>

                <div class="mt-6">

                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">

                        Guardar

                    </button>

                </div>

            </form>

    

  <hr class="my-8">

<div class="grid grid-cols-2 gap-8">

    <!-- FORMAS DE PAGO -->

    <div>

        <h2 class="text-lg font-bold mb-4">
            Formas de Pago
        </h2>


        <!-- NUEVA FORMA -->

        <form method="POST"
              action="{{ route('formas-pago.store') }}"
              class="flex items-center gap-2 mb-4">

            @csrf

            <input type="text"
                   name="nombre"
                   placeholder="Nueva forma de pago"
                   class="border rounded px-2 py-1 w-full text-sm">

            <button type="submit"
                    class="bg-green-500 text-white px-3 py-1 rounded text-sm">

                Agregar

            </button>

        </form>


        <!-- LISTADO -->

        <div class="space-y-2">

            @foreach($formasPago as $formaPago)

            <div class="flex items-center gap-2 border rounded p-2">

                <!-- EDITAR -->

                <form method="POST"
                      action="{{ route('formas-pago.update', $formaPago->id) }}"
                      class="flex items-center gap-2 w-full">

                    @csrf
                    @method('PUT')

                    <input type="text"
                           name="nombre"
                           value="{{ $formaPago->nombre }}"
                           class="border rounded px-2 py-1 w-full text-sm">

                    <button type="submit"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">

                        Actualizar

                    </button>

                </form>


                <!-- TOGGLE -->

                <form method="POST"
                      action="{{ route('formas-pago.toggle', $formaPago->id) }}">

                    @csrf

                    <button type="submit"
                            class="px-2 py-1 rounded text-white text-xs
                            {{ $formaPago->activo
                                ? 'bg-red-500'
                                : 'bg-green-500' }}">

                        {{ $formaPago->activo
                            ? 'OFF'
                            : 'ON' }}

                    </button>

                </form>

            </div>

            @endforeach

        </div>

    </div>


    <!-- EXTRAS -->

    <div>

        <h2 class="text-lg font-bold mb-4">
            Extras
        </h2>


        <!-- NUEVO EXTRA -->

        <form method="POST"
              action="{{ route('extras.store') }}"
              class="flex gap-2 mb-4">

            @csrf

            <input type="text"
                   name="nombre"
                   placeholder="Nombre extra"
                   class="border rounded px-2 py-1 w-full text-sm">

            <input type="number"
                   step="0.01"
                   name="precio"
                   placeholder="Precio"
                   class="border rounded px-2 py-1 w-32 text-sm">

            <button type="submit"
                    class="bg-purple-600 text-white px-3 py-1 rounded text-sm">

                Agregar

            </button>

        </form>


        <!-- LISTADO -->

        <div class="space-y-2">

            @foreach($extras as $extra)

            <div class="flex items-center gap-2 border rounded p-2">

                <form method="POST"
                      action="{{ route('extras.update', $extra->id) }}"
                      class="flex items-center gap-2 w-full">

                    @csrf
                    @method('PUT')

                    <input type="text"
                           name="nombre"
                           value="{{ $extra->nombre }}"
                           class="border rounded px-2 py-1 w-full text-sm">

                    <input type="number"
                           step="0.01"
                           name="precio"
                           value="{{ $extra->precio }}"
                           class="border rounded px-2 py-1 w-28 text-sm">

                    <button type="submit"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">

                        Actualizar

                    </button>

                </form>


                <form method="POST"
                      action="{{ route('extras.toggle', $extra->id) }}">

                    @csrf

                    <button type="submit"
                            class="px-2 py-1 rounded text-white text-xs
                            {{ $extra->activo
                                ? 'bg-red-500'
                                : 'bg-green-500' }}">

                        {{ $extra->activo
                            ? 'OFF'
                            : 'ON' }}

                    </button>

                </form>

            </div>

            @endforeach

        </div>

    </div>

</div>

</x-app-layout>