<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Reserva
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- ERRORES --}}

 @if(session('error'))

    <div class="bg-red-500 text-white p-4 rounded mb-4">

        {{ session('error') }}

    </div>

@endif


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

            <form action="{{ route('reservas.store') }}"
                  method="POST">

                @csrf


                {{-- CLIENTE --}}

                

                <div class="mb-4">

                    <label class="block mb-1">
                        Cliente
                    </label>

                    <select name="cliente_id"
                            class="w-full border rounded p-2">

                        @foreach($clientes as $cliente)

                            <option value="{{ $cliente->id }}">
                                {{ $cliente->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- HABITACIÓN --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Habitación
                    </label>

                    <select name="habitacion_id"
                            class="w-full border rounded p-2">

                        @foreach($habitaciones as $habitacion)

                            <option value="{{ $habitacion->id }}">
                                Habitación {{ $habitacion->numero }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- FECHA ENTRADA --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Fecha Entrada
                    </label>

                    <input type="date"
                           name="fecha_entrada"
                           class="w-full border rounded p-2">

                </div>


                {{-- FECHA SALIDA --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Fecha Salida
                    </label>

                    <input type="date"
                           name="fecha_salida"
                           class="w-full border rounded p-2">

                </div>


                {{-- CANTIDAD PERSONAS --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Cantidad Personas
                    </label>

                    <input type="number"
                           id="cantidad_personas"
                           name="cantidad_personas"
                           class="w-full border rounded p-2"
                           value="1"
                           min="1">

                </div>


                {{-- OBSERVACIONES --}}

                

                <div class="mb-4">

                    <label class="block mb-1">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="w-full border rounded p-2"></textarea>

                </div>

                

                

                {{-- DESCUENTO CLIENTE PRINCIPAL --}}

<div class="mb-4">

    <label class="block mb-1">
        Descuento Cliente Principal
    </label>

    <select
        name="descuento_principal_id"
        class="w-full border rounded p-2">

        <option value="">
            Sin descuento
        </option>

        @foreach($descuentos as $descuento)

            <option value="{{ $descuento->id }}">

                {{ $descuento->nombre }}

                -

                @if($descuento->tipo == 'porcentaje')

                    {{ $descuento->valor }}%

                @else

                    L. {{ number_format($descuento->valor, 2) }}

                @endif

            </option>

        @endforeach

    </select>

</div>


                {{-- HUÉSPEDES --}}

                <div id="listaHuespedes"
                     class="space-y-6">

                </div>


                {{-- BOTÓN --}}

                <div class="mt-6">

                    <button class="bg-blue-500 text-white px-4 py-2 rounded">

                        Guardar Reserva

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>

const paises = @json($paises);
const descuentos = @json($descuentos);

</script>


<script>

const cantidadInput = document.getElementById(
    'cantidad_personas'
);

const listaHuespedes = document.getElementById(
    'listaHuespedes'
);


// GENERAR BLOQUES

function generarHuespedes() {

    let opcionesDescuentos = `
    <option value="">
        Sin descuento
    </option>
`;

descuentos.forEach(descuento => {

    opcionesDescuentos += `
        <option value="${descuento.id}">

            ${descuento.nombre}

            - 

            ${
                descuento.tipo === 'porcentaje'
                ? descuento.valor + '%'
                : 'L. ' + descuento.valor
            }

        </option>
    `;

});

    let opcionesPaises = '';

paises.forEach(pais => {

    opcionesPaises += `
        <option value="${pais.nombre}">
            ${pais.bandera} ${pais.nombre}
        </option>
    `;

});


opcionesPaises += `
    <option value="OTRO">
        🌎 Otro...
    </option>
`;



let opcionesNacionalidades = '';

paises.forEach(pais => {

    opcionesNacionalidades += `
        <option value="${pais.nacionalidad}">
            ${pais.bandera} ${pais.nacionalidad}
        </option>
    `;

});

    const cantidad = parseInt(
        cantidadInput.value
    ) || 1;


    // GUARDAR DATOS EXISTENTES

    let datosActuales = {};

    document.querySelectorAll('.bloque-huesped')
        .forEach((bloque) => {

            const index = bloque.dataset.index;

            datosActuales[index] = {

                descuento_id:
    bloque.querySelector(
        `[name="huespedes[${index}][descuento_id]"]`
    )?.value || '',

                nombre:
                    bloque.querySelector(
                        `[name="huespedes[${index}][nombre]"]`
                    )?.value || '',

                identidad:
                    bloque.querySelector(
                        `[name="huespedes[${index}][identidad]"]`
                    )?.value || '',

                    telefono:
    bloque.querySelector(
        `[name="huespedes[${index}][telefono]"]`
    )?.value || '',

correo:
    bloque.querySelector(
        `[name="huespedes[${index}][correo]"]`
    )?.value || '',

direccion:
    bloque.querySelector(
        `[name="huespedes[${index}][direccion]"]`
    )?.value || '',

                nacionalidad:
                    bloque.querySelector(
                        `[name="huespedes[${index}][nacionalidad]"]`
                    )?.value || '',
pais_procedencia:
    bloque.querySelector(
        `[name="huespedes[${index}][pais_procedencia]"]`
    )?.value || '',

                genero:
                    bloque.querySelector(
                        `[name="huespedes[${index}][genero]"]`
                    )?.value || '',

                fecha_nacimiento:
                    bloque.querySelector(
                        `[name="huespedes[${index}][fecha_nacimiento]"]`
                    )?.value || ''

                    

            };

        });


    // LIMPIAR

    listaHuespedes.innerHTML = '';


    // SI SOLO ES 1 PERSONA

    if(cantidad <= 1) {

        return;
    }


    // GENERAR ACOMPAÑANTES

    for(let i = 2; i <= cantidad; i++) {

        const datos = datosActuales[i] || {};

        listaHuespedes.innerHTML += `

            <div
                class="bloque-huesped border rounded-lg p-5 bg-gray-50"
                data-index="${i}">

                <div class="flex items-center justify-between mb-4">

                    <h3 class="text-lg font-bold">

                        👤 Huésped ${i}

                    </h3>

                    <span class="text-sm text-gray-500">

                        Acompañante

                    </span>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>

                        <label class="block mb-1 text-sm">
                            Nombre
                        </label>

                        <input type="text"
                            name="huespedes[${i}][nombre]"
                            value="${datos.nombre || ''}"
                            class="w-full border rounded p-2"
                            required>

                    </div>


                    <div>

                        <label class="block mb-1 text-sm">
                            Identidad / Pasaporte
                        </label>

                        <input type="text"
                            name="huespedes[${i}][identidad]"
                            value="${datos.identidad || ''}"
                            class="w-full border rounded p-2"
                            required>

                    </div>

                    <div>

    <label class="block mb-1 text-sm">
        Teléfono
    </label>

    <input type="text"
        name="huespedes[${i}][telefono]"
        value="${datos.telefono || ''}"
        class="w-full border rounded p-2">

</div>


<div>

    <label class="block mb-1 text-sm">
        Correo Electrónico
    </label>

    <input type="email"
        name="huespedes[${i}][correo]"
        value="${datos.correo || ''}"
        class="w-full border rounded p-2">

</div>


                    <div>

                        <label class="block mb-1 text-sm">
                            Nacionalidad
                        </label>

                  <select
    name="huespedes[${i}][nacionalidad]"
    class="w-full border rounded p-2"
    required>

    ${opcionesNacionalidades}

</select>

                    </div>


                    <div>

                        <label class="block mb-1 text-sm">
                            País de Procedencia
                        </label>

                       <select
    name="huespedes[${i}][pais_procedencia]"
    class="w-full border rounded p-2"
    required>

    ${opcionesPaises}

</select>

                    </div>


                    <div>

                        <label class="block mb-1 text-sm">
                            Género
                        </label>

                        <select
                            name="huespedes[${i}][genero]"
                            class="w-full border rounded p-2"
                            required>

                            <option value="">
                                Seleccione
                            </option>

                            <option value="Masculino"
                                ${datos.genero === 'Masculino'
                                    ? 'selected'
                                    : ''}>

                                Masculino

                            </option>

                            <option value="Femenino"
                                ${datos.genero === 'Femenino'
                                    ? 'selected'
                                    : ''}>

                                Femenino

                            </option>

                        </select>

                    </div>

                    <div>

    <label class="block mb-1 text-sm">
        Descuento Habitación
    </label>

<select
    name="huespedes[${i}][descuento_id]"
    class="w-full border rounded p-2">

    <option value="">
        Sin descuento
    </option>

    ${descuentos.map(descuento => `

        <option
            value="${descuento.id}"

            ${datos.descuento_id == descuento.id
                ? 'selected'
                : ''}>

            ${descuento.nombre}

            -

            ${
                descuento.tipo === 'porcentaje'
                ? descuento.valor + '%'
                : 'L. ' + descuento.valor
            }

        </option>

    `).join('')}

</select>

</div>


                    <div>

                        <label class="block mb-1 text-sm">
                            Fecha Nacimiento
                        </label>

                        <input type="date"
                            name="huespedes[${i}][fecha_nacimiento]"
                            value="${datos.fecha_nacimiento || ''}"
                            class="w-full border rounded p-2"
                            required>

                    </div>

                    <div class="md:col-span-2">

    <label class="block mb-1 text-sm">
        Dirección
    </label>

    <textarea
        name="huespedes[${i}][direccion]"
        class="w-full border rounded p-2"
        rows="2">${datos.direccion || ''}</textarea>

</div>

                </div>

            </div>

        `;
    }


    // GUARDAR EN LOCAL STORAGE

  

}


// EVENTO CAMBIO

cantidadInput.addEventListener(
    'change',
    generarHuespedes
);


// AUTO GUARDADO




// RESTAURAR AL RECARGAR



</script>

</x-app-layout>