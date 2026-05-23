<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Reserva
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

            <form action="{{ route('reservas.update', $reserva->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- CLIENTE --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Cliente
                    </label>

                    <select name="cliente_id"
                            class="w-full border rounded p-2">

                        @foreach($clientes as $cliente)

                            <option value="{{ $cliente->id }}"
                                {{ $reserva->cliente_id == $cliente->id ? 'selected' : '' }}>

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

                            <option value="{{ $habitacion->id }}"
                                {{ $reserva->habitacion_id == $habitacion->id ? 'selected' : '' }}>

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
                           value="{{ $reserva->fecha_entrada }}"
                           class="w-full border rounded p-2">

                </div>


                {{-- FECHA SALIDA --}}

                <div class="mb-4">

                    <label class="block mb-1">
                        Fecha Salida
                    </label>

                    <input type="date"
                           name="fecha_salida"
                           value="{{ $reserva->fecha_salida }}"
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
                           value="{{ $reserva->cantidad_personas }}"
                           class="w-full border rounded p-2"
                           min="1">

                </div>


                {{-- OBSERVACIONES --}}

{{-- DESCUENTO CLIENTE PRINCIPAL --}}

@php

$huespedPrincipal = $reserva->huespedes
    ->where('identidad', $reserva->cliente->identidad)
    ->first();

@endphp

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

            <option
                value="{{ $descuento->id }}"

                {{
                    ($huespedPrincipal->descuento_id ?? null)
                    == $descuento->id
                    ? 'selected'
                    : ''
                }}>

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

                <div class="mb-4">

                    <label class="block mb-1">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="w-full border rounded p-2">{{ $reserva->observaciones }}</textarea>

                </div>


                {{-- HUÉSPEDES --}}

                <div id="listaHuespedes"
                     class="space-y-6">
                </div>


                {{-- BOTÓN --}}

                <div class="mt-6">

                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">

                        Actualizar Reserva

                    </button>

                </div>

            </form>

        </div>

    </div>


<script>

const cantidadInput = document.getElementById(
    'cantidad_personas'
);

const listaHuespedes = document.getElementById(
    'listaHuespedes'
);


// DATOS DESDE LARAVEL

const huespedesExistentes = @json(

    $reserva->huespedes->skip(1)->values()

);

const paises = @json($paises);
const descuentos = @json($descuentos);


// GENERAR HUÉSPEDES

function generarHuespedes() {

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

let opcionesDescuentos = `
<option value="">
    Sin descuento
</option>
`;

descuentos.forEach(desc => {

    opcionesDescuentos += `
        <option value="${desc.id}">
            ${desc.nombre}
        </option>
    `;

});

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


    // GUARDAR DATOS ACTUALES

    let datosActuales = {};


    document.querySelectorAll('.bloque-huesped')
        .forEach((bloque) => {

            const index = bloque.dataset.index;

            datosActuales[index] = {

                nombre:
                    bloque.querySelector(
                        `[name="huespedes[${index}][nombre]"]`
                    )?.value || '',

                identidad:
                    bloque.querySelector(
                        `[name="huespedes[${index}][identidad]"]`
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

    descuento_id:
    bloque.querySelector(
        `[name="huespedes[${index}][descuento_id]"]`
    )?.value || '',

                fecha_nacimiento:
                    bloque.querySelector(
                        `[name="huespedes[${index}][fecha_nacimiento]"]`
                    )?.value || ''

            };

        });


    // SI NO HAY DATOS ESCRITOS
    // CARGAR LOS DE BD

    if(Object.keys(datosActuales).length === 0) {

        huespedesExistentes.forEach((huesped, idx) => {

            datosActuales[idx + 2] = huesped;

        });

    }


    // LIMPIAR

    listaHuespedes.innerHTML = '';


    // SI SOLO ES 1 PERSONA

    if(cantidad <= 1) {

        return;

    }


    // GENERAR BLOQUES

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
                            Identidad
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
        Correo
    </label>

    <input type="email"
        name="huespedes[${i}][correo]"
        value="${datos.correo || ''}"
        class="w-full border rounded p-2">

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


                    <div>

                        <label class="block mb-1 text-sm">
                            Nacionalidad
                        </label>

                       <select
    name="huespedes[${i}][nacionalidad]"
    class="w-full border rounded p-2"
    required>

   ${paises.map(pais => `
    <option value="${pais.nacionalidad}"
        ${datos.nacionalidad === pais.nacionalidad
            ? 'selected'
            : ''}>

        ${pais.bandera} ${pais.nacionalidad}

    </option>
`).join('')}

</select>

                    </div>


                    <div>

                        <label class="block mb-1 text-sm">
                            País Procedencia
                        </label>

                   <select
    name="huespedes[${i}][pais_procedencia]"
    class="w-full border rounded p-2"
    required>

   ${paises.map(pais => `
    <option value="${pais.nombre}"
        ${datos.pais_procedencia === pais.nombre
            ? 'selected'
            : ''}>

        ${pais.bandera} ${pais.nombre}

    </option>
`).join('')}

<option value="OTRO">
    🌎 Otro...
</option>

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

                    <div>

    <label class="block mb-1 text-sm">
        Descuento
    </label>

    <select
        name="huespedes[${i}][descuento_id]"
        class="w-full border rounded p-2">

        <option value="">
            Sin descuento
        </option>

        ${descuentos.map(desc => `

            <option value="${desc.id}"
                ${datos.descuento_id == desc.id
                    ? 'selected'
                    : ''}>

                ${desc.nombre}

            </option>

        `).join('')}

    </select>

</div>

                        <label class="block mb-1 text-sm">
                            Fecha Nacimiento
                        </label>

                        <input type="date"
                            name="huespedes[${i}][fecha_nacimiento]"
                            value="${datos.fecha_nacimiento || ''}"
                            class="w-full border rounded p-2"
                            required>

                    </div>

                </div>

            </div>

        `;
    }

}


// CAMBIO DE CANTIDAD

cantidadInput.addEventListener(
    'change',
    generarHuespedes
);


// CARGA INICIAL

window.addEventListener('load', () => {

    generarHuespedes();

});

</script>

</x-app-layout>