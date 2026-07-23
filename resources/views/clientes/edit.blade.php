<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- ERRORES --}}

        @if ($errors->any())

            <div class="bg-red-500 text-white p-4 rounded mb-4">

                <ul class="list-disc pl-5">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('clientes.update', $cliente->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- NOMBRE --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Nombre Completo
                        </label>

                        <input type="text"
                               name="nombre"
                               value="{{ old('nombre', $cliente->nombre) }}"
                               class="w-full border rounded p-2"
                               required>

                    </div>


                    {{-- IDENTIDAD --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Identidad / Pasaporte
                        </label>

                        <input type="text"
                               name="identidad"
                               value="{{ old('identidad', $cliente->identidad) }}"
                               class="w-full border rounded p-2">

                    </div>

                       <div>
                        <label class="block mb-1" for="fecha_vencimiento_documento">
                            Fecha de vencimiento del documento
                        </label>

                        <input 
                            type="date"
                            name="fecha_vencimiento_documento"
                            id="fecha_vencimiento_documento"
                             class="w-full border rounded p-2"
                               value="{{ old('fecha_vencimiento_documento', $cliente->fecha_vencimiento_documento) }}">             
                        
                        </div>


                    {{-- RTN --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            RTN
                        </label>

                        <input type="text"
                               name="rtn"
                               value="{{ old('rtn', $cliente->rtn) }}"
                               class="w-full border rounded p-2">

                    </div>




                    {{-- TELÉFONO --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Teléfono
                        </label>

                        <input type="text"
                               name="telefono"
                               value="{{ old('telefono', $cliente->telefono) }}"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- CORREO --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Correo Electrónico
                        </label>

                        <input type="email"
                               name="correo"
                               value="{{ old('correo', $cliente->correo) }}"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- GÉNERO --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Género
                        </label>

                        <select name="genero"
                                class="w-full border rounded p-2">

                            <option value="">
                                Seleccione
                            </option>

                            <option value="Masculino"
                                {{ old('genero', $cliente->genero) == 'Masculino' ? 'selected' : '' }}>

                                Masculino

                            </option>

                            <option value="Femenino"
                                {{ old('genero', $cliente->genero) == 'Femenino' ? 'selected' : '' }}>

                                Femenino

                            </option>

                        </select>

                    </div>


                    {{-- PAÍS PROCEDENCIA --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            País de Procedencia
                        </label>

                        <select id="pais_procedencia"
                                class="w-full border rounded p-2">

                            @foreach($paises as $pais)

                                <option
                                    value="{{ $pais->nombre }}"
                                    {{ old('pais_procedencia', $cliente->pais_procedencia) == $pais->nombre ? 'selected' : '' }}>

                                    {{ $pais->bandera }}
                                    {{ $pais->nombre }}

                                </option>

                            @endforeach

                            <option value="OTRO">
                                🌎 Otro...
                            </option>

                        </select>


                        {{-- INPUT REAL --}}
                        <input type="hidden"
                               name="pais_procedencia"
                               id="pais_hidden"
                               value="{{ old('pais_procedencia', $cliente->pais_procedencia) }}">

                    </div>


                    {{-- NACIONALIDAD --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Nacionalidad
                        </label>

                        <select name="nacionalidad"
                                class="w-full border rounded p-2">

                            @foreach($paises as $pais)

                                <option
                                    value="{{ $pais->nacionalidad }}"
                                    {{ old('nacionalidad', $cliente->nacionalidad) == $pais->nacionalidad ? 'selected' : '' }}>

                                    {{ $pais->bandera }}
                                    {{ $pais->nacionalidad }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- OTRO PAÍS --}}

                    <div id="otroPaisContainer"
                         class="hidden">

                        <label class="block mb-1 font-semibold">
                            Otro País
                        </label>

                        <input type="text"
                               id="otroPais"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- FECHA NACIMIENTO --}}

                    <div>

                        <label class="block mb-1 font-semibold">
                            Fecha de Nacimiento
                        </label>

                        <input type="date"
                               name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}"
                               class="w-full border rounded p-2">

                    </div>

                </div>


                {{-- DIRECCIÓN --}}

                <div class="mt-6">

                    <label class="block mb-1 font-semibold">
                        Dirección
                    </label>

                    <textarea name="direccion"
                              rows="4"
                              class="w-full border rounded p-2">{{ old('direccion', $cliente->direccion) }}</textarea>

                </div>


                {{-- BOTÓN --}}

                <div class="mt-6">

                    <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded shadow">

                        Actualizar Cliente

                    </button>

                </div>

            </form>

        </div>

    </div>


<script>

const paisSelect = document.getElementById(
    'pais_procedencia'
);

const otroPaisContainer = document.getElementById(
    'otroPaisContainer'
);

const otroPaisInput = document.getElementById(
    'otroPais'
);

const paisHidden = document.getElementById(
    'pais_hidden'
);


// ACTUALIZAR PAÍS

function actualizarPais() {

    const opcion =
        paisSelect.options[
            paisSelect.selectedIndex
        ];


    // SI ES OTRO

    if(opcion.value === 'OTRO') {

        otroPaisContainer.classList.remove(
            'hidden'
        );

        paisHidden.value = '';

        return;
    }


    // OCULTAR

    otroPaisContainer.classList.add(
        'hidden'
    );


    // GUARDAR PAÍS

    paisHidden.value =
        opcion.value;

}


// CAMBIO SELECT

paisSelect.addEventListener(
    'change',
    actualizarPais
);


// ESCRIBIR OTRO PAÍS

otroPaisInput.addEventListener(
    'input',
    () => {

        paisHidden.value =
            otroPaisInput.value;

    }
);


// CARGA INICIAL

window.addEventListener(
    'load',
    actualizarPais
);

</script>

</x-app-layout>