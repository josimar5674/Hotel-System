<x-app-layout>

    @error('identidad')

        <div class="text-red-500 text-sm mt-1">

            {{ $message }}

        </div>

    @enderror


    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Nuevo Cliente

        </h2>

    </x-slot>


    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('clientes.store') }}"
                  method="POST">

                @csrf


                {{-- GRID 2 COLUMNAS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- NOMBRE --}}

                    <div>

                        <label class="block mb-1">

                            Nombre

                        </label>

                        <input type="text"
                               name="nombre"
                               class="w-full border rounded p-2"
                               required>

                    </div>


                    {{-- IDENTIDAD --}}

                    <div>

                        <label class="block mb-1">

                            Identidad || Pasaporte

                        </label>

                        <input type="text"
                               name="identidad"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- RTN --}}

                    <div>

                        <label class="block mb-1">

                            RTN

                        </label>

                        <input type="text"
                               name="rtn"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- TELÉFONO --}}

                    <div>

                        <label class="block mb-1">

                            Teléfono

                        </label>

                        <input type="text"
                               name="telefono"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- CORREO --}}

                    <div>

                        <label class="block mb-1">

                            Correo

                        </label>

                        <input type="email"
                               name="correo"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- GÉNERO --}}

                    <div>

                        <label class="block mb-1">

                            Género

                        </label>

                        <select name="genero"
                                class="w-full border rounded p-2">

                            <option value="Masculino">

                                Masculino

                            </option>

                            <option value="Femenino">

                                Femenino

                            </option>

                        </select>

                    </div>


                    {{-- PAÍS PROCEDENCIA --}}

                    <div>

                        <label class="block mb-1">

                            País de Procedencia

                        </label>

                        <select id="pais_procedencia"
                                class="w-full border rounded p-2">

                            @foreach($paises as $pais)

                                <option
                                    value="{{ $pais->nombre }}"
                                    {{ $pais->nombre == 'Honduras' ? 'selected' : '' }}>

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
                               value="Honduras">

                    </div>


                    {{-- NACIONALIDAD --}}

                    <div>

                        <label class="block mb-1">

                            Nacionalidad

                        </label>

                        <select name="nacionalidad"
                                class="w-full border rounded p-2">

                            @foreach($paises as $pais)

                                <option
                                    value="{{ $pais->nacionalidad }}"
                                    {{ $pais->nombre == 'Honduras' ? 'selected' : '' }}>

                                    {{ $pais->bandera }}
                                    {{ $pais->nacionalidad }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- OTRO PAÍS --}}

                    <div id="otroPaisContainer"
                         class="hidden">

                        <label class="block mb-1">

                            Otro País

                        </label>

                        <input type="text"
                               id="otroPais"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- FECHA NACIMIENTO --}}

                    <div>

                        <label class="block mb-1">

                            Fecha Nacimiento

                        </label>

                        <input type="date"
                               name="fecha_nacimiento"
                               class="w-full border rounded p-2">

                    </div>


                    {{-- DIRECCIÓN --}}
                    {{-- OCUPA 2 COLUMNAS --}}

                    <div class="md:col-span-2">

                        <label class="block mb-1">

                            Dirección

                        </label>

                        <textarea name="direccion"
                                  class="w-full border rounded p-2"
                                  rows="3"></textarea>

                    </div>

                </div>


                {{-- BOTÓN --}}

                <div class="mt-6">

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow">

                        Guardar Cliente

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


// ACTUALIZAR

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


    // GUARDAR VALOR

    paisHidden.value =
        opcion.value;

}


// CAMBIO

paisSelect.addEventListener(
    'change',
    actualizarPais
);


// ESCRIBIR OTRO

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