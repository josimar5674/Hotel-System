<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte Contable
        </h2>
    </x-slot>

    <div class="space-y-6">

        {{-- FILTROS --}}

        <div class="bg-white rounded-xl shadow p-5 flex-1">

            <form method="GET" action="{{ route('reportes.contable') }}">

                <div class="flex flex-wrap items-end gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Desde
                        </label>

                        <input
                            type="date"
                            class="mt-1 border-gray-300 rounded-lg"
                            name="desde"
                            value="{{ $fechaDesde }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Hasta
                        </label>

                        <input
                            type="date"
                            class="mt-1 border-gray-300 rounded-lg"
                            name="hasta"
                            value="{{ $fechaHasta }}">
                    </div>

                    <button
                        type="submit"
                        class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-800">

                        Consultar

                    </button>

                </div>

        </div>

        </form>
        {{-- RESUMEN --}}

        <div class="flex gap-4">
            <div class="bg-white rounded-xl shadow p-5 flex-1">

                <p class="text-sm text-gray-500">
                    Total facturado
                </p>

                <p class="text-3xl font-bold mt-2">
                    L {{ number_format($totalFacturado, 2) }}
                </p>

            </div>


            <div class="bg-white rounded-xl shadow p-5 flex-1">

                <p class="text-sm text-gray-500">
                    Total impuestos
                </p>

                <p class="text-3xl font-bold mt-2">
                    L {{ number_format($totalImpuestos, 2) }}
                </p>

            </div>


            <div class="bg-white rounded-xl shadow p-5 flex-1">

                <p class="text-sm text-gray-500">
                    Facturas emitidas
                </p>

                <p class="text-3xl font-bold mt-2">
                    {{ $cantidadFacturas }}
                </p>

            </div>

        </div>




        {{-- GRÁFICO PRINCIPAL --}}

        <div class="bg-white rounded-xl shadow p-5">

            <h3 class="text-lg font-semibold">
                Facturación por día
            </h3>
            <div class="h-96 w-full">

                <canvas
                    id="facturacionPorDia"
                    class="w-full h-full">
                </canvas>

            </div>



        </div>

    </div>




    {{-- GRÁFICOS SECUNDARIOS --}}

    <div class="grid grid-cols-2 gap-6 mt-6">

        <div class="bg-white rounded-xl shadow p-5 flex-1">

            <h3 class="text-lg font-semibold">
                Facturación por forma de pago
            </h3>

            <div class="h-80 w-full">
                <canvas id="facturacionPorFormaPago"></canvas>
            </div>
        </div>


        <div class="bg-white rounded-xl shadow p-5 flex-1">

            <h3 class="text-lg font-semibold">
                Distribución de impuestos
            </h3>
            <div class="h-80 w-full">

                <canvas id="distribucionImpuestos"></canvas>

            </div>

        </div>

    </div>


    {{-- DETALLE --}}

<div class="bg-white rounded-xl shadow p-5 flex-1 mt-6">
        <div class="flex justify-between items-center mb-4">

            <h3 class="text-lg font-semibold">
                Detalle de facturación
            </h3>

            <div class="flex gap-2">

               <a
    href="{{ route('reportes.contable.excel', request()->query()) }}"
    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

    Excel

</a>

               

            </div>

        </div>
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead>

                    <tr class="border-b border-gray-200 text-left">

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Factura
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Fecha
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Cliente
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Subtotal
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Impuestos
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Total
                        </th>

                        <th class="px-4 py-3 font-semibold text-gray-600">
                            Estado
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($detalleFacturacion as $factura)

                    <tr class="border-b border-gray-100 hover:bg-gray-50">

                        <td class="px-4 py-3 font-medium">

                            {{ $factura->numero_factura }}

                        </td>

                        <td class="px-4 py-3">

                            {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}

                        </td>

                        <td class="px-4 py-3">

                            {{ $factura->cliente->nombre ?? 'Sin cliente' }}

                        </td>

                        <td class="px-4 py-3">

                            L {{ number_format($factura->subtotal, 2) }}

                        </td>

                        <td class="px-4 py-3">

                            L {{ number_format($factura->total_impuestos, 2) }}

                        </td>

                        <td class="px-4 py-3 font-semibold">

                            L {{ number_format($factura->total, 2) }}

                        </td>

                        <td class="px-4 py-3">

                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">

                                {{ ucfirst($factura->estado) }}

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-4 py-10 text-center text-gray-400">

                            No hay facturas para el período seleccionado.

                        </td>

                    </tr>

                    @endforelse

                </tbody>
                <div class="mt-6">

                    {{ $detalleFacturacion->links() }}

                </div>

            </table>

        </div>

    </div>

    </div>

</x-app-layout>
<style>
    #facturacionPorDia {
        width: 100% !important;
        height: 100% !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        console.log('Inicializando gráfico...');

        console.log('Chart:', window.Chart);

        const datos = @json($facturacionPorDia);

        console.log('Datos:', datos);

        const canvas = document.getElementById('facturacionPorDia');

        console.log('Canvas:', canvas);

        if (!canvas) {
            console.error('No se encontró el canvas facturacionPorDia');
            return;
        }

        if (!window.Chart) {
            console.error('Chart.js todavía no está disponible');
            return;
        }

        const ctx = canvas.getContext('2d');

        new window.Chart(ctx, {

            type: 'line',

            data: {

                labels: datos.map(item => item.fecha),

                datasets: [{

                    label: 'Facturación',

                    data: datos.map(item => Number(item.total)),

                    fill: true,

                    tension: 0.3,

                    pointRadius: 4,

                    pointHoverRadius: 7

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {

                    legend: {
                        display: true
                    },

                    tooltip: {

                        enabled: true,

                        callbacks: {

                            title: function(context) {

                                const index =
                                    context[0].dataIndex;

                                return 'Fecha: ' +
                                    datos[index].fecha;

                            },

                            label: function(context) {

                                const index =
                                    context.dataIndex;

                                const dato =
                                    datos[index];

                                const formatoMoneda = function(valor) {

                                    return Number(valor)
                                        .toLocaleString('es-HN', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });

                                };

                                return [
                                    'Facturas: ' + dato.cantidad,
                                    'Subtotal: L ' + formatoMoneda(dato.subtotal),
                                    'Impuestos: L ' + formatoMoneda(dato.impuestos),
                                    'Total facturado: L ' + formatoMoneda(dato.total)
                                ];

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return 'L ' +
                                    Number(value)
                                    .toLocaleString('es-HN');

                            }

                        }

                    }

                }

            }

        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        console.log('Inicializando gráfico forma de pago...');

        console.log(
            'Datos forma de pago:',
            @json($facturacionPorFormaPago)
        );

        console.log(
            'Chart:',
            window.Chart
        );

        const datosFormaPago =
            @json($facturacionPorFormaPago);

        console.log('DATOS FORMA DE PAGO:', datosFormaPago);

        const canvasFormaPago =
            document.getElementById(
                'facturacionPorFormaPago'
            );

        console.log(
            'Canvas forma de pago:',
            canvasFormaPago
        );

        if (!canvasFormaPago) {

            console.error(
                'No se encontró el canvas facturacionPorFormaPago'
            );

            return;
        }

        if (!window.Chart) {

            console.error(
                'Chart.js no está disponible'
            );

            return;
        }

        const ctxFormaPago =
            canvasFormaPago.getContext('2d');

        new window.Chart(ctxFormaPago, {

            type: 'bar',

            data: {

                labels: datosFormaPago.map(
                    item => item.forma_pago
                ),

                datasets: [{

                    label: 'Facturación',

                    data: datosFormaPago.map(
                        item => Number(item.total)
                    ),

                    borderWidth: 1

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        enabled: true,

                        callbacks: {

                            title: function(context) {

                                return context[0].label;

                            },

                            label: function(context) {

                                const dato =
                                    datosFormaPago[
                                        context.dataIndex
                                    ];

                                const formatoMoneda =
                                    function(valor) {

                                        return Number(valor)
                                            .toLocaleString('es-HN', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });

                                    };

                                return [

                                    'Facturas: ' +
                                    dato.cantidad,

                                    'Total facturado: L ' +
                                    formatoMoneda(dato.total)

                                ];

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return 'L ' +
                                    Number(value)
                                    .toLocaleString('es-HN');

                            }

                        }

                    }

                }

            }

        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const distribucionImpuestos =
            @json($distribucionImpuestos);

        const canvas =
            document.getElementById('distribucionImpuestos');

        if (!canvas) {
            console.error(
                'No se encontró el canvas distribucionImpuestos'
            );
            return;
        }

        if (!window.Chart) {
            console.error(
                'Chart.js no está disponible'
            );
            return;
        }

        const valores =
            distribucionImpuestos.map(
                item => Number(item.total)
            );

        const totalImpuestos =
            valores.reduce(
                (total, valor) => total + valor,
                0
            );

        new window.Chart(canvas, {

            type: 'doughnut',

            data: {

                labels: distribucionImpuestos.map(
                    item => item.tipo
                ),

                datasets: [{

                    data: valores,

                    borderWidth: 2

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom'

                    },

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                const valor =
                                    Number(context.raw);

                                const porcentaje =
                                    totalImpuestos > 0 ?
                                    (
                                        valor /
                                        totalImpuestos *
                                        100
                                    ).toFixed(2) :
                                    0;

                                const formatoMoneda =
                                    valor.toLocaleString(
                                        'es-HN', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }
                                    );

                                return [
                                    context.label,

                                    'Monto: L ' +
                                    formatoMoneda,

                                    'Participación: ' +
                                    porcentaje + '%'
                                ];

                            }

                        }

                    }

                }

            }

        });

    });
</script>