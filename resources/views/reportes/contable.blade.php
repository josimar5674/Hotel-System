<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte Contable
        </h2>
    </x-slot>
    <div class="space-y-6">
        {{-- FILTROS --}}
        <div class="bg-white rounded-xl shadow p-5">
            <form method="GET" action="{{ route('reportes.contable') }}">
                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="desde" class="block text-sm font-medium text-gray-700">
                            Desde
                        </label>
                        <input
                            id="desde"
                            type="date"
                            name="desde"
                            value="{{ $fechaDesde }}"
                            class="mt-1 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label for="hasta" class="block text-sm font-medium text-gray-700">
                            Hasta
                        </label>
                        <input
                            id="hasta"
                            type="date"
                            name="hasta"
                            value="{{ $fechaHasta }}"
                            class="mt-1 border-gray-300 rounded-lg">
                    </div>
                    <button
                        type="submit"
                        class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-800">
                        Consultar
                    </button>
                </div>
            </form>
        </div>
{{-- FILA 1: INDICADORES REALES CON DIFERENCIAS --}}
<div class="grid grid-cols-3 gap-2 md:gap-4" >
    {{-- TOTAL FACTURADO REAL --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-green-500 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Total facturado real</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            L {{ number_format($totalFacturado, 2) }}
        </p>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-red-600 font-medium">
                Diferencia: L {{ number_format($totalFacturadoOriginal - $totalFacturado, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Respecto al original</p>
        </div>
    </div>
    {{-- IMPUESTOS REALES --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-blue-500 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Total impuestos real</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            L {{ number_format($totalImpuestos, 2) }}
        </p>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-red-600 font-medium">
                Diferencia: L {{ number_format($totalImpuestosOriginal - $totalImpuestos, 2) }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Respecto al original</p>
        </div>
    </div>
    {{-- FACTURAS REALES --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-purple-500 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Facturas emitidas reales</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            {{ number_format($cantidadFacturas) }}
        </p>
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-red-600 font-medium">
                Diferencia: {{ number_format($cantidadFacturasOriginal - $cantidadFacturas) }} facturas
            </p>
            <p class="text-xs text-gray-400 mt-1">Respecto al original</p>
        </div>
    </div>
</div>
{{-- FILA 2: INDICADORES ORIGINALES --}}
<div class="grid grid-cols-3 gap-2 md:gap-4">
    {{-- TOTAL FACTURADO ORIGINAL --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-gray-400 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Total facturado original</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            L {{ number_format($totalFacturadoOriginal, 2) }}
        </p>
        <p class="hidden md:block text-xs text-gray-500 mt-2">
            Incluye facturas anuladas
        </p>
    </div>
    {{-- IMPUESTOS ORIGINALES --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-orange-500 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Total impuestos original</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            L {{ number_format($totalImpuestosOriginal, 2) }}
        </p>
        <p class="hidden md:block text-xs text-gray-500 mt-2">
            Incluye impuestos de facturas anuladas
        </p>
    </div>
    {{-- FACTURAS ORIGINALES --}}
    <div class="bg-white rounded-xl shadow p-3 md:p-5 border-l-4 border-red-500 min-w-0">
        <p class="text-xs md:text-sm text-gray-500">Facturas originales</p>
        <p class="text-base md:text-3xl font-bold mt-2 text-gray-800 break-words">
            {{ number_format($cantidadFacturasOriginal) }}
        </p>
        <p class="hidden md:block text-xs text-gray-500 mt-2">
            Incluye todas las facturas registradas
        </p>
    </div>
</div>

</div>
        {{-- GRÁFICO PRINCIPAL: ORIGINAL VS REAL --}}
       <div class="bg-white rounded-xl shadow p-5 mt-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-5">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Facturación: real vs. original
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Comparación diaria de la facturación y el impacto de las anulaciones.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 mb-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <span class="text-gray-600">Facturación original</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-600"></span>
                    <span class="text-gray-600">Facturación real</span>
                </div>
            </div>
            <div class="h-80 md:h-96 w-full">
                <canvas id="facturacionPorDia"></canvas>
            </div>
        </div>
        {{-- GRÁFICOS SECUNDARIOS EN UNA SOLA FILA --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="text-lg font-semibold text-gray-800">
                    Facturación por forma de pago
                </h3>
                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Distribución de pagos de las facturas vigentes.
                </p>
                <div class="h-72 w-full">
                    <canvas id="facturacionPorFormaPago"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="text-lg font-semibold text-gray-800">
                    Distribución de impuestos
                </h3>
                <p class="text-sm text-gray-500 mt-1 mb-4">
                    Impuestos correspondientes a facturas vigentes.
                </p>
                <div class="h-72 w-full">
                    <canvas id="distribucionImpuestos"></canvas>
                </div>
            </div>
        </div>
        {{-- TABLA DE FACTURAS --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-5">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Detalle de facturación
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Historial de facturas emitidas y anuladas.
                    </p>
                </div>
                <a
                    href="{{ route('reportes.contable.excel', request()->query()) }}"
                    class="inline-flex justify-center items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Exportar a Excel
                </a>
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
                            <th class="px-4 py-3 font-semibold text-gray-600 text-center">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detalleFacturacion as $factura)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium whitespace-nowrap">
                                    {{ $factura->numero_factura }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $factura->cliente->nombre ?? 'Sin cliente' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    L {{ number_format($factura->subtotal, 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    L {{ number_format($factura->total_impuestos, 2) }}
                                </td>
                                <td class="px-4 py-3 font-semibold whitespace-nowrap">
                                    L {{ number_format($factura->total, 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($factura->estado === 'emitida')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Emitida
                                        </span>
                                    @elseif($factura->estado === 'anulada')
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Anulada
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                            {{ ucfirst($factura->estado) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if($factura->estado === 'emitida')
                                        <button
                                            type="button"
                                            onclick="abrirModalAnulacion(
                                                {{ $factura->id }},
                                                @js($factura->numero_factura)
                                            )"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs">
                                            Anular factura
                                        </button>
                                    @elseif($factura->estado === 'anulada')
                                        <span class="text-gray-500 text-xs">
                                            Sin acciones
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                    No hay facturas para el período seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- PAGINACIÓN --}}
            <div class="mt-6">
                {{ $detalleFacturacion->withQueryString()->links() }}
            </div>
        </div>
    </div>
    {{-- MODAL PARA ANULAR FACTURA --}}
    <div
        id="modalAnulacion"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-xl font-bold mb-2">
                Anular factura
            </h2>
            <p class="text-sm text-gray-500 mb-4">
                Esta operación conservará la factura en el historial.
            </p>
            <form
                id="formAnulacion"
                method="POST"
                action="">
                @csrf
                <div class="mb-4">
                    <label
                        for="numeroFacturaAnular"
                        class="block text-sm font-medium mb-1">
                        Número de factura
                    </label>
                    <input
                        type="text"
                        id="numeroFacturaAnular"
                        readonly
                        class="w-full border-gray-300 rounded-lg bg-gray-100">
                </div>
                <div class="mb-5">
                    <label
                        for="motivo_anulacion"
                        class="block text-sm font-medium mb-1">
                        Motivo de anulación
                    </label>
                    <textarea
                        id="motivo_anulacion"
                        name="motivo_anulacion"
                        required
                        maxlength="1000"
                        rows="4"
                        class="w-full border-gray-300 rounded-lg"
                        placeholder="Explique por qué se anula la factura..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        onclick="cerrarModalAnulacion()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Confirmar anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
{{-- MODAL: FUNCIONES --}}
<script>
    function abrirModalAnulacion(id, numero) {
        const modal = document.getElementById('modalAnulacion');
        const formulario = document.getElementById('formAnulacion');
        formulario.action = `/facturas/${id}/anular`;
        document.getElementById('numeroFacturaAnular').value = numero;
        document.getElementById('motivo_anulacion').value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function cerrarModalAnulacion() {
        const modal = document.getElementById('modalAnulacion');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalAnulacion();
        }
    });
    document.getElementById('modalAnulacion').addEventListener('click', function(event) {
        if (event.target === this) {
            cerrarModalAnulacion();
        }
    });
</script>
{{-- GRÁFICO PRINCIPAL: ORIGINAL VS. REAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datos = @json($facturacionPorDia);
        const canvas = document.getElementById('facturacionPorDia');
        if (!canvas || !window.Chart) {
            console.error('No se encontró el canvas o Chart.js no está disponible.');
            return;
        }
        const moneda = valor => Number(valor).toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: datos.map(item => item.fecha),
                datasets: [
                    {
                        label: 'Facturación original',
                        data: datos.map(item => Number(item.original)),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Facturación real',
                        data: datos.map(item => Number(item.real)),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.08)',
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: false
                    }
                ]
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
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return 'Fecha: ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': L ' + moneda(context.raw);
                            },
                            afterBody: function(context) {
                                const dato = datos[context[0].dataIndex];
                                return [
                                    'Facturas originales: ' + dato.cantidad_original,
                                    'Facturas vigentes: ' + dato.cantidad_real,
                                    'Importe anulado: L ' + moneda(dato.anuladas)
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
                                return 'L ' + Number(value).toLocaleString('es-HN');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
{{-- GRÁFICO: FORMA DE PAGO --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datos = @json($facturacionPorFormaPago);
        const canvas = document.getElementById('facturacionPorFormaPago');
        if (!canvas || !window.Chart) {
            console.error('No se encontró el canvas o Chart.js no está disponible.');
            return;
        }
        const moneda = valor => Number(valor).toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: datos.map(item => item.forma_pago),
                datasets: [{
                    label: 'Facturación',
                    data: datos.map(item => Number(item.total)),
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
                        callbacks: {
                            label: function(context) {
                                const dato = datos[context.dataIndex];
                                return [
                                    'Facturas: ' + dato.cantidad,
                                    'Total: L ' + moneda(dato.total)
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
                                return 'L ' + Number(value).toLocaleString('es-HN');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
{{-- GRÁFICO: DISTRIBUCIÓN DE IMPUESTOS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datos = @json($distribucionImpuestos);
        const canvas = document.getElementById('distribucionImpuestos');
        if (!canvas || !window.Chart) {
            console.error('No se encontró el canvas o Chart.js no está disponible.');
            return;
        }
        const valores = datos.map(item => Number(item.total));
        const totalImpuestos = valores.reduce((total, valor) => total + valor, 0);
        const moneda = valor => Number(valor).toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: datos.map(item => item.tipo),
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
                                const valor = Number(context.raw);
                                const porcentaje = totalImpuestos > 0
                                    ? (valor / totalImpuestos * 100).toFixed(2)
                                    : '0.00';
                                return [
                                    context.label,
                                    'Monto: L ' + moneda(valor),
                                    'Participación: ' + porcentaje + '%'
                                ];
                            }
                        }
                    }
                }
            }
        });
    });
</script>