<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reservas
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- MENSAJES --}}

        @if(session('success'))

        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>

        @endif

        @if(session('error'))

        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>

        @endif


        {{-- BOTON NUEVA RESERVA --}}

        <div class="mb-4">

            <a href="{{ route('reservas.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded">

                Nueva Reserva

            </a>

        </div>

        {{-- CALENDARIO RESERVAS --}}

<div class="bg-white shadow rounded-lg p-4 mb-8">

    <h3 class="text-lg font-bold mb-4">
        Calendario de Ocupación
    </h3>

    <div id="calendar"></div>

</div>


        {{-- RESERVAS ACTIVAS --}}

        <div class="bg-white shadow rounded-lg p-4 mb-8">

            <h3 class="text-lg font-bold mb-4">
                Reservas Activas
            </h3>

            <table class="w-full border">

                <thead>

                    <tr class="bg-gray-200">

                        <th class="p-2 text-center">Cliente</th>

                        <th class="p-2 text-center">Habitación</th>

                        <th class="p-2 text-center">Entrada</th>
                        <th class="p-2 text-center">Salida</th>

                        <th class="p-2 text-center">Personas/Noches</th>
                        <th class="p-2 text-center">
                            Descuentos
                        </th>

                        <th class="p-2 text-center">Total</th>
                        <th class="p-2 text-center">Estado</th>

                        <th class="p-2 text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($reservasActivas as $reserva)

                    @php

                    $entrada = \Carbon\Carbon::parse($reserva->fecha_entrada);

                    $salida = \Carbon\Carbon::parse($reserva->fecha_salida);

                    $noches = $entrada->diffInDays($salida);


                    @endphp

                    <tr class="border-t">

                        <td class="p-2 text-center">
                            {{ $reserva->cliente->nombre }}
                        </td>

                        <td class="p-2 text-center">
                            {{ $reserva->habitacion->numero }}
                        </td>

                        <td class="p-2 text-center">
                            {{ $reserva->fecha_entrada }}
                        </td>

                        <td class="p-2 text-center">
                            {{ $reserva->fecha_salida }}
                        </td>

                        <td class="p-2 text-center">
                            {{ $reserva->cantidad_personas }}

                            Personas

                            /

                            {{ $reserva->noches }} Noches


                        </td>
                        <td class="p-2 text-center">

                          @php

// DESCUENTOS HABITACIÓN

$descuentosHabitacion = DB::table(
    'reserva_huespedes'
)
->where('reserva_id', $reserva->id)
->sum('descuento_monto');

$descuentosHabitacion = (
    $descuentosHabitacion *
    $reserva->noches
);


// DESCUENTOS EXTRAS

$descuentosExtras = 0;

foreach($reserva->extras as $extra) {

    $descuentosExtras += (

        ($extra->pivot->descuento_monto ?? 0)

        *

        $extra->pivot->cantidad

    );

}


// TOTAL DESCUENTOS

$totalDescuentos = (
    $descuentosHabitacion +
    $descuentosExtras
);

@endphp

                            <span class="text-red-600 font-bold">

                                - L. {{ number_format(
            $totalDescuentos,
            2
        ) }}

                            </span>

                        </td>

                        <td class="p-2 text-center align-middle">

                            L. {{ number_format(
        $reserva->total_factura,
        2
    ) }}

                        </td>

                        <td class="p-2 text-center">
                            {{ $reserva->estado }}
                        </td>

                        <td class="p-2 text-center">
                            <div class="flex justify-center items-center gap-2 flex-wrap">

                                @if($reserva->estado == 'reservada')

                                <a href="{{ route('reservas.edit', $reserva->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded">

                                    Editar

                                </a>

                                <form action="{{ route('reservas.destroy', $reserva->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">

                                        Eliminar

                                    </button>

                                </form>

                                @endif


                                @if($reserva->estado == 'reservada')

                                <form action="{{ route('reservas.checkin', $reserva->id) }}"
                                    method="POST">

                                    @csrf

                                    <button type="submit"
                                        class="bg-green-600 text-white px-3 py-1 rounded">

                                        Check-In

                                    </button>

                                </form>

                                @endif
                                <a href="{{ route('reservas.extras', $reserva->id) }}"
                                    class="bg-purple-600 text-white px-3 py-1 rounded">

                                    Extras

                                </a>

                                @if($reserva->estado == 'checkin')

                                <button
                                    type="button"
                                    onclick='abrirModalCheckout(

    {{ $reserva->id }},

    {{ $reserva->subtotal_factura }},

    {{ $reserva->impuesto_15 }},

    {{ $reserva->impuesto_turismo }},

    {{ $reserva->total_factura }},

    {{ $reserva->habitacion->precio }},

    {{ $noches }},

{{ DB::table("reserva_huespedes")
    ->where("reserva_id", $reserva->id)
    ->sum("descuento_monto") * $noches }},

    @json($reserva->extras)

)'

                                    class="bg-blue-600 text-white px-3 py-1 rounded">

                                    Check-Out

                                </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>



        {{-- HISTORIAL --}}

        <div class="bg-white shadow rounded-lg p-4">

            <h3 class="text-lg font-bold mb-4">
                Historial
            </h3>

            <div class="mb-4">

                <input type="text"
                    id="buscarHistorial"
                    placeholder="Buscar en historial..."
                    class="w-full border rounded p-2">

            </div>

            <table class="w-full border">

                <thead>

                    <tr class="bg-gray-200">

                        <th class="p-2">Cliente</th>

                        <th class="p-2">Habitación</th>

                        <th class="p-2">Entrada</th>

                        <th class="p-2">Salida</th>

                        <th class="p-2">Check-In</th>

                        <th class="p-2">Check-Out</th>

                        <th class="p-2">Usuario Check-In</th>

                        <th class="p-2">Noches</th>

                        <th class="p-2">Total</th>

                        <th class="p-2">Estado</th>
                        <th class="p-2">Factura</th>

                    </tr>

                </thead>

                <tbody id="tablaHistorial">

                    @foreach($reservasHistorial as $reserva)

                @php

$entrada = \Carbon\Carbon::parse($reserva->fecha_entrada);

$salida = \Carbon\Carbon::parse($reserva->fecha_salida);

$noches = $entrada->diffInDays($salida);


// TOTAL FACTURA REAL

$total = $reserva->factura->total ?? 0;

@endphp

                    <tr class="border-t">

                        <td class="p-2">
                            {{ $reserva->cliente->nombre }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->habitacion->numero }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->fecha_entrada }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->fecha_salida }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->fecha_checkin }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->fecha_checkout }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->usuarioCheckin->name ?? 'N/A' }}
                        </td>

                        <td class="p-2">
                            {{ $noches }}
                        </td>

                        <td class="p-2">
                            L. {{ number_format($total, 2) }}
                        </td>

                        <td class="p-2">
                            {{ $reserva->estado }}
                        </td>

                        <td class="p-2">

                            <div class="flex gap-2">

                                @if($reserva->factura)

                                <a href="{{ route('facturas.pdf', $reserva->factura->id) }}"
                                    target="_blank"
                                    class="bg-red-500 text-white px-3 py-1 rounded flex items-center gap-1 w-fit">

                                    🖨️ PDF

                                </a>

                                @endif


                                <a href="{{ route(
            'reservas.registro-huesped',
            $reserva->id
        ) }}"
                                    target="_blank"
                                    class="bg-gray-700 text-white px-3 py-1 rounded flex items-center gap-1 w-fit">

                                    🧾 Registro

                                </a>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
            <div class="mt-4">

                {{ $reservasHistorial->links() }}

            </div>
        </div>

    </div>

    <!-- MODAL CHECKOUT -->
    <!-- MODAL CHECKOUT -->

    <div id="modalCheckout"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div class="bg-white rounded-lg shadow-lg p-6 w-[600px]">

            <h2 class="text-lg font-bold mb-4">
                Formas de Pago
            </h2>

            <div class="bg-gray-100 rounded p-3 mb-4 text-sm">

                <div id="extrasContainer"
                    class="mt-3 space-y-1">

                </div>


            </div>

            <form id="formCheckout"
                method="POST">

                @csrf

                <div id="pagosContainer">

                    <div class="flex gap-2 mb-2 pago-row">

                        <select name="forma_pago_id[]"
                            class="border rounded p-2 w-full">

                            @foreach($formasPago as $formaPago)

                            <option value="{{ $formaPago->id }}">

                                {{ $formaPago->nombre }}

                            </option>

                            @endforeach

                        </select>

                        <input type="number"
                            step="0.01"
                            name="monto[]"
                            placeholder="Monto"
                            class="border rounded p-2 w-40 monto-input">

                        <button type="button"
                            onclick="eliminarPago(this)"
                            class="bg-red-500 text-white px-3 rounded">

                            ✕

                        </button>

                    </div>

                </div>

                <div class="bg-gray-100 rounded p-3 mb-4 text-sm">

                    <div class="flex justify-between">

                        <span>Total ingresado:</span>

                        <span id="totalIngresadoTexto">

                            L. 0.00

                        </span>

                    </div>

                    <div class="flex justify-between font-bold">

                        <span>Restante:</span>

                        <span id="restanteTexto">

                            L. 0.00

                        </span>

                    </div>

                </div>


                <button type="button"
                    onclick="agregarPago()"
                    class="bg-green-500 text-white px-3 py-1 rounded mb-4">

                    + Agregar Pago

                </button>
                <div id="errorPagos"
                    class="hidden bg-red-500 text-white p-2 rounded mb-4 text-sm">

                    La suma de pagos debe ser igual al total de la factura.

                </div>

                <div class="flex justify-end gap-2">

                    <button type="button"
                        onclick="cerrarModalCheckout()"
                        class="bg-gray-400 text-white px-4 py-2 rounded">

                        Cancelar

                    </button>

                    <button type="submit"
                        id="btnConfirmarCheckout"
                        disabled
                        class="bg-gray-400 text-white px-4 py-2 rounded disabled:opacity-50">

                        Confirmar Checkout

                    </button>

                </div>

            </form>

        </div>

    </div>





    <script>
        let totalFactura = 0;

        function abrirModalCheckout(
            id,
            subtotal,
            isv,
            turismo,
            total,
            precioHabitacion,
            noches,
            totalDescuentos,
            extras
        ) {
            totalFactura = parseFloat(total);

            const modal = document.getElementById(
                'modalCheckout'
            );

            const form = document.getElementById(
                'formCheckout'
            );

            form.action = '/reservas/' + id + '/checkout';


           


// =====================================
// DETALLE FACTURA
// =====================================

const extrasContainer = document.getElementById(
    'extrasContainer'
);

extrasContainer.innerHTML = '';


// ================================
// HABITACIÓN
// ================================

const totalHabitacion = (
    precioHabitacion * noches
);

extrasContainer.innerHTML += `

<div class="border-b pb-2 mb-2">

    <div class="flex justify-between text-sm font-semibold">

        <span>
            🛏️ Habitación (${noches} noche${noches > 1 ? 's' : ''})
        </span>

        <span>
            L. ${totalHabitacion.toFixed(2)}
        </span>

    </div>

</div>

`;


// ================================
// EXTRAS
// ================================

let totalDescuentosExtras = 0;

if (extras.length > 0) {

    extrasContainer.innerHTML += `

    <div class="text-sm font-bold mt-2 mb-2">

        Extras

    </div>

    `;

    extras.forEach(extra => {

        const subtotalExtra = (
            extra.pivot.cantidad *
            extra.pivot.precio
        );

        const descuentoExtra = (
            extra.pivot.descuento_monto || 0
        ) * extra.pivot.cantidad;

        const totalExtra = (
            subtotalExtra -
            descuentoExtra
        );

        totalDescuentosExtras += descuentoExtra;

        extrasContainer.innerHTML += `

        <div class="flex justify-between text-sm">

            <span>
                ${extra.nombre}
                x${extra.pivot.cantidad}
            </span>

            <span>
                L. ${totalExtra.toFixed(2)}
            </span>

        </div>

        `;

    });

}


// ================================
// TOTAL DESCUENTOS
// ================================

const descuentosTotales = (
    parseFloat(totalDescuentos) +
    totalDescuentosExtras
);

if (descuentosTotales > 0) {

    extrasContainer.innerHTML += `

    <div class="flex justify-between text-sm text-red-600 font-bold border-t pt-2 mt-3">

        <span>
            💸 Total Descuentos
        </span>

        <span>
            - L. ${descuentosTotales.toFixed(2)}
        </span>

    </div>

    `;

}


// ================================
// RESUMEN FACTURA
// ================================

extrasContainer.innerHTML += `

<div class="border-t mt-3 pt-3 space-y-1">

    <div class="flex justify-between text-sm">

        <span>Subtotal:</span>

        <span>
            L. ${parseFloat(subtotal).toFixed(2)}
        </span>

    </div>

    <div class="flex justify-between text-sm">

        <span>ISV:</span>

        <span>
            L. ${parseFloat(isv).toFixed(2)}
        </span>

    </div>

    <div class="flex justify-between text-sm">

        <span>Impuesto Turismo:</span>

        <span>
            L. ${parseFloat(turismo).toFixed(2)}
        </span>

    </div>

    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">

        <span>TOTAL FINAL:</span>

        <span>
            L. ${parseFloat(total).toFixed(2)}
        </span>

    </div>

</div>

`;


            // Limpiar mensajes

            const errorPagos = document.getElementById(
                'errorPagos'
            );

            if (errorPagos) {

                errorPagos.classList.add('hidden');

            }


            modal.classList.remove('hidden');
        }

        function cerrarModalCheckout() {
            document.getElementById('modalCheckout')
                .classList.add('hidden');
        }


        function agregarPago() {
            const container = document.getElementById('pagosContainer');

            const row = document.querySelector('.pago-row')
                .cloneNode(true);

            row.querySelector('input').value = '';

            container.appendChild(row);

            agregarEventosMontos();

            recalcularPagos();
        }


        function eliminarPago(button) {
            const rows = document.querySelectorAll('.pago-row');

            if (rows.length <= 1) {

                return;

            }

            button.closest('.pago-row').remove();

            recalcularPagos();
        }


        function agregarEventosMontos() {
            document.querySelectorAll('.monto-input')
                .forEach(input => {

                    input.removeEventListener(
                        'input',
                        recalcularPagos
                    );

                    input.addEventListener(
                        'input',
                        recalcularPagos
                    );

                });
        }


        function recalcularPagos() {
            let totalPagos = 0;

            document.querySelectorAll('.monto-input')
                .forEach(input => {

                    totalPagos += parseFloat(
                        input.value || 0
                    );

                });


            const restante = (
                totalFactura - totalPagos
            );


            document.getElementById(
                    'totalIngresadoTexto'
                ).innerText =

                'L. ' + totalPagos.toFixed(2);


            document.getElementById(
                    'restanteTexto'
                ).innerText =

                'L. ' + restante.toFixed(2);


            const btn = document.getElementById(
                'btnConfirmarCheckout'
            );


            if (Math.abs(restante) <= 0.01) {

                btn.disabled = false;

                btn.classList.remove(
                    'bg-gray-400'
                );

                btn.classList.add(
                    'bg-blue-600'
                );

            } else {

                btn.disabled = true;

                btn.classList.add(
                    'bg-gray-400'
                );

                btn.classList.remove(
                    'bg-blue-600'
                );
            }
        }


        // Inicializar listeners

        agregarEventosMontos();


        // VALIDAR SUBMIT

        document.getElementById('formCheckout')
            .addEventListener('submit', function(e) {

                let totalPagos = 0;

                document.querySelectorAll('.monto-input')
                    .forEach(input => {

                        totalPagos += parseFloat(
                            input.value || 0
                        );

                    });


                if (Math.abs(totalPagos - totalFactura) > 0.01) {

                    e.preventDefault();

                    document.getElementById('errorPagos')
                        .classList.remove('hidden');

                }

            });


        // VALIDAR TOTAL PAGOS

        document.getElementById('formCheckout')
            .addEventListener('submit', function(e) {

                let totalPagos = 0;

                document.querySelectorAll('input[name="monto[]"]')
                    .forEach(input => {

                        totalPagos += parseFloat(input.value || 0);

                    });


                if (Math.abs(totalPagos - totalFactura) > 0.01) {

                    e.preventDefault();

                    document.getElementById('errorPagos')
                        .classList.remove('hidden');

                    return;
                }

            });

        // BUSCADOR HISTORIAL

        document.getElementById('buscarHistorial')
            .addEventListener('keyup', function() {

                const filtro = this.value.toLowerCase();

                const filas = document.querySelectorAll(
                    '#tablaHistorial tr'
                );

                filas.forEach(fila => {

                    const texto = fila.innerText
                        .toLowerCase();

                    if (texto.includes(filtro)) {

                        fila.style.display = '';

                    } else {

                        fila.style.display = 'none';

                    }

                });

            });
    </script>

    <script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

const calendar = new FullCalendar.Calendar(calendarEl, {

    initialView: 'dayGridMonth',

    locale: 'es',

    height: 500,

    contentHeight: 450,

    aspectRatio: 1.8,

    events: @json($eventosCalendario),

    eventDisplay: 'block',

    headerToolbar: {

        left: 'prev,next today',

        center: 'title',

        right: 'dayGridMonth,timeGridWeek'

    }

});

    calendar.render();

});

</script>

</x-app-layout>