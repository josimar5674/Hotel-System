<?php

namespace App\Http\Controllers;


use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Factura;
use App\Models\ConfiguracionFiscal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\FormaPago;
use App\Models\Pago;
use App\Models\Extra;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pais;
use App\Models\Descuento;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $config = ConfiguracionFiscal::first();


        // Reservas activas

        $reservasActivas = Reserva::with([

            'cliente',

            'habitacion',

            'extras'

        ])

            ->whereIn('estado', [
                'reservada',
                'checkin'
            ])

            ->orderBy('fecha_entrada', 'asc')

            ->get();


        // Calcular totales dinámicos

        foreach ($reservasActivas as $reserva) {

            $entrada = Carbon::parse(
                $reserva->fecha_entrada
            );

            $salida = Carbon::parse(
                $reserva->fecha_salida
            );

            $noches = $entrada->diffInDays($salida);


            // Subtotal hospedaje

          // =====================================
// SUBTOTAL HABITACIÓN
// =====================================

$subtotalHabitacion = (
    $noches *
    $reserva->habitacion->precio
);


// =====================================
// DESCUENTOS HABITACIÓN
// =====================================

$totalDescuentosHabitacion = DB::table(
    'reserva_huespedes'
)
->where('reserva_id', $reserva->id)
->sum('descuento_monto');

$totalDescuentoFinal = (
    $totalDescuentosHabitacion *
    $noches
);


// SUBTOTAL HABITACIÓN FINAL
// YA CON DESCUENTO

$subtotal = (
    $subtotalHabitacion -
    $totalDescuentoFinal
);

            // Extras

            $totalExtras = 0;


            foreach ($reserva->extras as $extra) {

                $subtotalExtra = (

                    $extra->pivot->cantidad *

                    $extra->pivot->precio

                );

                $descuentoExtra = (

                    $extra->pivot->descuento_monto ?? 0

                ) * $extra->pivot->cantidad;

                $totalExtra = (
                    $subtotalExtra -
                    $descuentoExtra
                );

                $totalExtras += $totalExtra;
            }


            // Subtotal general

            $subtotal += $totalExtras;


            // Impuesto 15%

            $impuesto15 = $subtotal * (
                $config->impuesto_15 / 100
            );


            // Impuesto turismo

            $impuestoTurismo = $subtotal * (
                $config->impuesto_turismo / 100
            );


            // Total impuestos

            $totalImpuestos = (
                $impuesto15 +
                $impuestoTurismo
            );


            // Total final factura

            $total = (
                $subtotal +
                $totalImpuestos
            );


            // Variables frontend

            $reserva->noches = $noches;

            $reserva->extras_total = $totalExtras;
            $reserva->descuentos_habitacion = $totalDescuentoFinal;

            $reserva->subtotal_factura = $subtotal;

            $reserva->impuesto_15 = $impuesto15;

            $reserva->impuesto_turismo = $impuestoTurismo;

            $reserva->total_factura = $total;
        }


        // Historial

        // Historial

        $reservasHistorial = Reserva::with([

            'cliente',

            'habitacion',

            'usuarioCheckin',

            'usuarioCheckout',

            'factura'

        ])

            ->whereIn('estado', [

                'checkout',

                'cancelada'

            ])

            ->orderBy('fecha_checkout', 'desc')

            ->paginate(20);

        // Formas de pago activas

        $formasPago = FormaPago::where(
            'activo',
            true
        )->get();

$eventosCalendario = [];

foreach ($reservasActivas as $reserva) {

    $color = '#facc15'; // amarilla reservada

    if ($reserva->estado == 'checkin') {

        $color = '#22c55e'; // verde
    }

    $eventosCalendario[] = [

        'title' => 'Hab. ' .
            $reserva->habitacion->numero .
            ' - ' .
            $reserva->cliente->nombre,

        'start' => $reserva->fecha_entrada,

        'end' => Carbon::parse(
            $reserva->fecha_salida
        )->addDay()->format('Y-m-d'),

        'backgroundColor' => $color,

        'borderColor' => $color,

    ];
}
        return view('reservas.index', compact(

            'reservasActivas',

            'reservasHistorial',

            'formasPago',
            'eventosCalendario'

        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::latest()->get();

        $habitaciones = Habitacion::whereNotIn('estado', [
            'mantenimiento'
        ])->get();

        $descuentos = Descuento::where(
            'activo',
            true
        )->get();

        $paises = Pais::orderByRaw("
        CASE
            WHEN nombre = 'Honduras' THEN 1
            WHEN nombre = 'Estados Unidos' THEN 2
            ELSE 3
        END
    ")
            ->orderBy('nombre')
            ->get();


        return view('reservas.create', compact(
            'clientes',
            'habitaciones',
            'paises',
            'descuentos'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            // VALIDACIONES

            $request->validate([

                'cliente_id' => 'required',

                'habitacion_id' => 'required',

                'fecha_entrada' => 'required|date',

                'fecha_salida' => 'required|date|after:fecha_entrada',

                'cantidad_personas' => 'required|integer|min:1',

            ]);


            // VALIDAR CONFLICTO

            $reservaExistente = Reserva::where(

                'habitacion_id',

                $request->habitacion_id

            )

                ->where('estado', '!=', 'cancelada')

                ->where(function ($query) use ($request) {

                    $query->where(

                        'fecha_entrada',

                        '<',

                        $request->fecha_salida

                    )

                        ->where(

                            'fecha_salida',

                            '>',

                            $request->fecha_entrada

                        );
                })

                ->exists();


            if ($reservaExistente) {

                return back()

                    ->withInput()

                    ->with(

                        'error',

                        'La habitación ya está reservada en esas fechas'

                    );
            }


            // VALIDAR HUÉSPEDES

            if ($request->cantidad_personas > 1) {

                if (!$request->has('huespedes')) {

                    return back()

                        ->withInput()

                        ->withErrors([

                            'error' => 'Debe ingresar los acompañantes.'

                        ]);
                }
            }

            // CREAR RESERVA


            $reserva = Reserva::create([

                'cliente_id' => $request->cliente_id,

                'habitacion_id' => $request->habitacion_id,

                'fecha_entrada' => $request->fecha_entrada,

                'fecha_salida' => $request->fecha_salida,

                'cantidad_personas' => $request->cantidad_personas,

                'estado' => 'reservada',

                'observaciones' => $request->observaciones,

            ]);


            // GUARDAR HUÉSPEDES

            // ======================================
            // GUARDAR CLIENTE PRINCIPAL COMO HUÉSPED
            // ======================================

            $cliente = Cliente::find(
                $request->cliente_id
            );



            $habitacion = Habitacion::find(
                $request->habitacion_id
            );

            $precioPorPersona = (
                $habitacion->precio /
                $request->cantidad_personas
            );
            $descuentoPrincipalMonto = 0;

            if ($request->descuento_principal_id) {

                $descuento = Descuento::find(
                    $request->descuento_principal_id
                );

                if ($descuento) {

                    if ($descuento->tipo == 'porcentaje') {

                        $descuentoPrincipalMonto = (

                            $precioPorPersona *

                            ($descuento->valor / 100)

                        );
                    } else {

                        $descuentoPrincipalMonto =
                            $descuento->valor;
                    }
                }
            }

            DB::table('reserva_huespedes')->insert([

                'reserva_id' => $reserva->id,

                'nombre' => $cliente->nombre,

                'identidad' => $cliente->identidad,
                'telefono' => $cliente->telefono,

                'correo' => $cliente->correo,

                'direccion' => $cliente->direccion,

                'nacionalidad' => $cliente->nacionalidad,

                'pais_procedencia' => $cliente->pais_procedencia,

                'genero' => $cliente->genero,

                'fecha_nacimiento' => $cliente->fecha_nacimiento,

                'descuento_id' =>
                $request->descuento_principal_id,

                'descuento_monto' =>
                $descuentoPrincipalMonto,

                'created_at' => now(),

                'updated_at' => now(),

            ]);


            // ======================================
            // GUARDAR ACOMPAÑANTES
            // ======================================
            $habitacion = Habitacion::find(
                $request->habitacion_id
            );

            $precioPorPersona = (
                $habitacion->precio /
                $request->cantidad_personas
            );



            if ($request->has('huespedes')) {

                foreach ($request->huespedes as $huesped) {


                    $descuentoMonto = 0;

                    if (!empty($huesped['descuento_id'])) {

                        $descuento = Descuento::find(
                            $huesped['descuento_id']
                        );

                        if ($descuento) {

                            if ($descuento->tipo == 'porcentaje') {

                                $descuentoMonto = (

                                    $precioPorPersona *

                                    ($descuento->valor / 100)

                                );
                            } else {

                                $descuentoMonto =
                                    $descuento->valor;
                            }
                        }
                    }

                    DB::table('reserva_huespedes')->insert([

                        'reserva_id' => $reserva->id,

                        'nombre' => $huesped['nombre'],

                        'identidad' => $huesped['identidad'],

                        'telefono' => $huesped['telefono'] ?? null,
                        'correo' => $huesped['correo'] ?? null,
                        'direccion' => $huesped['direccion'] ?? null,

                        'nacionalidad' => $huesped['nacionalidad'],

                        'pais_procedencia' => $huesped['pais_procedencia'],

                        'genero' => $huesped['genero'],

                        'fecha_nacimiento' => $huesped['fecha_nacimiento'],

                        'descuento_id' =>
                        $huesped['descuento_id'] ?? null,

                        'descuento_monto' =>
                        $descuentoMonto,

                        'created_at' => now(),

                        'updated_at' => now(),



                    ]);
                }
            }


            return redirect()

                ->route('reservas.index')

                ->with(

                    'success',

                    'Reserva creada correctamente'

                );
        } catch (\Exception $e) {

            return back()

                ->withInput()

                ->withErrors([

                    'error' => $e->getMessage()

                ]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        if ($reserva->estado == 'checkin') {

            abort(403);
        }

        // CARGAR RELACIÓN



        $reserva->load('huespedes');


        $descuentos = Descuento::where(
            'activo',
            true
        )->get();

        $clientes = Cliente::latest()->get();

        $habitaciones = Habitacion::whereNotIn('estado', [
            'mantenimiento'
        ])->get();

        $paises = Pais::orderByRaw("
    CASE
        WHEN nombre = 'Honduras' THEN 1
        WHEN nombre = 'Estados Unidos' THEN 2
        ELSE 3
    END
")
            ->orderBy('nombre')
            ->get();


        return view('reservas.edit', compact(

            'reserva',

            'clientes',

            'habitaciones',

            'paises',
            'descuentos'

        ));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Reserva $reserva)
{
    // VALIDACIONES

    $request->validate([

        'cliente_id' => 'required',

        'habitacion_id' => 'required',

        'fecha_entrada' => 'required|date',

        'fecha_salida' => 'required|date|after:fecha_entrada',

        'cantidad_personas' => 'required|integer|min:1',

    ]);


    // VALIDAR CONFLICTO

    $reservaExistente = Reserva::where(
        'habitacion_id',
        $request->habitacion_id
    )

        ->where('id', '!=', $reserva->id)

        ->where('estado', '!=', 'cancelada')

        ->where(function ($query) use ($request) {

            $query->where(
                'fecha_entrada',
                '<',
                $request->fecha_salida
            )

            ->where(
                'fecha_salida',
                '>',
                $request->fecha_entrada
            );
        })

        ->exists();


    if ($reservaExistente) {

        return back()

            ->withInput()

            ->with(
                'error',
                'La habitación ya está reservada en esas fechas'
            );
    }


    // ACTUALIZAR RESERVA

    $reserva->update([

        'cliente_id' => $request->cliente_id,

        'habitacion_id' => $request->habitacion_id,

        'fecha_entrada' => $request->fecha_entrada,

        'fecha_salida' => $request->fecha_salida,

        'cantidad_personas' => $request->cantidad_personas,

        'observaciones' => $request->observaciones,

    ]);


    // ELIMINAR HUÉSPEDES ACTUALES

    DB::table('reserva_huespedes')
        ->where('reserva_id', $reserva->id)
        ->delete();


    // HABITACIÓN

    $habitacion = Habitacion::find(
        $request->habitacion_id
    );

    $precioPorPersona = (
        $habitacion->precio /
        $request->cantidad_personas
    );


    // ======================================
    // INSERTAR CLIENTE PRINCIPAL
    // ======================================

    $cliente = Cliente::find(
        $request->cliente_id
    );

    $descuentoPrincipalMonto = 0;

    if ($request->descuento_principal_id) {

        $descuento = Descuento::find(
            $request->descuento_principal_id
        );

        if ($descuento) {

            // PORCENTAJE

            if ($descuento->tipo == 'porcentaje') {

                $descuentoPrincipalMonto = (

                    $precioPorPersona *

                    ($descuento->valor / 100)

                );

            }

            // MONTO FIJO

            else {

                $descuentoPrincipalMonto =
                    $descuento->valor;
            }
        }
    }

    DB::table('reserva_huespedes')->insert([

        'reserva_id' => $reserva->id,

        'nombre' => $cliente->nombre,

        'identidad' => $cliente->identidad,

        'telefono' => $cliente->telefono,

        'correo' => $cliente->correo,

        'direccion' => $cliente->direccion,

        'nacionalidad' => $cliente->nacionalidad,

        'pais_procedencia' => $cliente->pais_procedencia,

        'genero' => $cliente->genero,

        'fecha_nacimiento' => $cliente->fecha_nacimiento,

        'descuento_id' =>
        $request->descuento_principal_id,

        'descuento_monto' =>
        $descuentoPrincipalMonto,

        'created_at' => now(),

        'updated_at' => now(),

    ]);


    // ======================================
    // INSERTAR ACOMPAÑANTES
    // ======================================

    if ($request->has('huespedes')) {

        foreach ($request->huespedes as $huesped) {

            $descuentoMonto = 0;

            if (!empty($huesped['descuento_id'])) {

                $descuento = Descuento::find(
                    $huesped['descuento_id']
                );

                if ($descuento) {

                    // PORCENTAJE

                    if ($descuento->tipo == 'porcentaje') {

                        $descuentoMonto = (

                            $precioPorPersona *

                            ($descuento->valor / 100)

                        );
                    }

                    // MONTO FIJO

                    else {

                        $descuentoMonto =
                            $descuento->valor;
                    }
                }
            }

            DB::table('reserva_huespedes')->insert([

                'reserva_id' => $reserva->id,

                'nombre' => $huesped['nombre'],

                'identidad' => $huesped['identidad'],

                'telefono' => $huesped['telefono'],

                'correo' => $huesped['correo'],

                'direccion' => $huesped['direccion'],

                'nacionalidad' => $huesped['nacionalidad'],

                'pais_procedencia' => $huesped['pais_procedencia'],

                'genero' => $huesped['genero'],

                'fecha_nacimiento' => $huesped['fecha_nacimiento'],

                'descuento_id' =>
                $huesped['descuento_id'] ?? null,

                'descuento_monto' =>
                $descuentoMonto,

                'created_at' => now(),

                'updated_at' => now(),

            ]);
        }
    }


    return redirect()

        ->route('reservas.index')

        ->with(
            'success',
            'Reserva actualizada correctamente'
        );
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        if ($reserva->estado == 'checkin') {

            abort(403);
        }

        $reserva->delete();

        return redirect()
            ->route('reservas.index')
            ->with('success', 'Reserva eliminada');
    }

    public function checkin(Reserva $reserva)
    {
        // Validar estado actual

        if ($reserva->estado == 'checkin') {

            return back()->with(
                'error',
                'La reserva ya hizo check-in'
            );
        }


        // Cambiar reserva
        $reserva->update([

            'estado' => 'checkin',

            'fecha_checkin' => now(),

            'usuario_checkin_id' => Auth::id()

        ]);

        // Cambiar habitación

        $reserva->habitacion->update([

            'estado' => 'ocupada'

        ]);


        return back()->with(
            'success',
            'Check-In realizado correctamente'
        );
    }
    public function extras(Reserva $reserva)
    {
        $reserva->load('extras');

        $extras = Extra::where(
            'activo',
            true
        )->get();


        $descuentos = Descuento::where(
            'activo',
            true
        )->get();


        return view(
            'reservas.extras',
            compact(
                'reserva',
                'extras',
                'descuentos'
            )
        );
    }


    public function storeExtra(
        Request $request,
        Reserva $reserva
    ) {

        $extra = Extra::findOrFail(
            $request->extra_id
        );


        $descuentoMonto = 0;


        // DESCUENTO

        if ($request->descuento_id) {

            $descuento = Descuento::find(
                $request->descuento_id
            );


            if ($descuento) {

                // PORCENTAJE

                if ($descuento->tipo == 'porcentaje') {

                    $descuentoMonto = (

                        $extra->precio *

                        ($descuento->valor / 100)

                    );
                }

                // MONTO FIJO

                else {

                    $descuentoMonto =

                        $descuento->valor;
                }
            }
        }


        $reserva->extras()->attach(

            $extra->id,

            [

                'cantidad' => $request->cantidad,

                'precio' => $extra->precio,

                'descuento_id' => $request->descuento_id,

                'descuento_monto' => $descuentoMonto

            ]

        );


        return back()->with(

            'success',

            'Extra agregado correctamente'

        );
    }


    public function checkout(Reserva $reserva)
    {

        $reserva->load('extras');

        // Validar

        if ($reserva->estado != 'checkin') {

            return back()->with(
                'error',
                'La reserva no está en check-in'
            );
        }


        DB::transaction(function () use ($reserva) {

            // Configuración fiscal

            $config = ConfiguracionFiscal::lockForUpdate()->first();


            // Validar fecha límite CAI

            if (

                Carbon::parse($config->fecha_limite)

                ->endOfDay()

                ->isPast()

            ) {
                abort(500, 'El CAI está vencido');
            }


            // Tomar correlativo actual

            $numeroFactura = $config->siguiente_numero;


            // Calcular noches

            $entrada = Carbon::parse($reserva->fecha_entrada);

            $salida = Carbon::parse($reserva->fecha_salida);

            $noches = $entrada->diffInDays($salida);



            // Calcular subtotal


            // SUBTOTAL HABITACIÓN

            $subtotalHabitacion = (
                $noches *
                $reserva->habitacion->precio
            );


            // DESCUENTOS HABITACIÓN

            $totalDescuentosHabitacion = DB::table(
                'reserva_huespedes'
            )
                ->where('reserva_id', $reserva->id)
                ->sum('descuento_monto');


            // TOTAL DESCUENTO

            $totalDescuentoFinal = (
                $totalDescuentosHabitacion *
                $noches
            );


// SUBTOTAL FINAL
// YA CON DESCUENTO

$subtotal = (
    $subtotalHabitacion -
    $totalDescuentoFinal
);

            // Extras


            // Extras

            $totalExtras = 0;

            foreach ($reserva->extras as $extra) {

                $subtotalExtra = (

                    $extra->pivot->cantidad *

                    $extra->pivot->precio

                );

                $descuentoExtra = (

                    $extra->pivot->descuento_monto ?? 0

                ) * $extra->pivot->cantidad;

                $totalExtra = (
                    $subtotalExtra -
                    $descuentoExtra
                );

                $totalExtras += $totalExtra;
            }


            // Subtotal general

            $subtotal += $totalExtras;


            // Impuestos

            $impuesto15 = $subtotal * (
                $config->impuesto_15 / 100
            );

            $impuestoTurismo = $subtotal * (
                $config->impuesto_turismo / 100
            );


            $totalImpuestos = (
                $impuesto15 +
                $impuestoTurismo
            );

// TOTAL CON IMPUESTOS
// TOTAL FINAL

$total = (
    $subtotal +
    $totalImpuestos
);

            $totalPagos = collect(
                request('monto')
            )->sum();

            if (abs($totalPagos - $total) > 0.01) {

                abort(500, 'La suma de pagos no coincide con el total');
            }

            // Crear factura

            $factura = Factura::create([



                'reserva_id' => $reserva->id,

                'cliente_id' => $reserva->cliente_id,

                'usuario_id' => Auth::id(),

                'numero_factura' => $numeroFactura,

                'cai' => $config->cai,

                'fecha_limite' => $config->fecha_limite,

                'subtotal' => $subtotal,

                'impuesto_15' => $impuesto15,

                'impuesto_18' => 0,

                'impuesto_turismo' => $impuestoTurismo,

                'total_impuestos' => $totalImpuestos,

                'total' => $total,

                'estado' => 'emitida',

                'fecha_emision' => now(),

            ]);


            foreach (request('forma_pago_id') as $index => $formaPagoId) {

                Pago::create([

                    'factura_id' => $factura->id,

                    'forma_pago_id' => $formaPagoId,

                    'monto' => request('monto')[$index]

                ]);
            }

            // Incrementar correlativo

            // Separar partes

            $partes = explode('-', $config->siguiente_numero);


            // Última parte numérica

            $correlativo = (int) end($partes);


            // Incrementar

            $correlativo++;


            // Mantener ceros

            $partes[count($partes) - 1] = str_pad(
                $correlativo,
                8,
                '0',
                STR_PAD_LEFT
            );


            // Reconstruir número

            $nuevoNumero = implode('-', $partes);


            // Guardar

            $config->update([

                'siguiente_numero' => $nuevoNumero

            ]);


            // Actualizar reserva

            $reserva->update([

                'estado' => 'checkout',

                'fecha_checkout' => now(),

                'usuario_checkout_id' => Auth::id()

            ]);


            // Liberar habitación

            $reserva->habitacion->update([

                'estado' => 'disponible'

            ]);
        });


        return back()->with(
            'success',
            'Check-Out y factura generada correctamente'
        );
    }



    public function registroHuespedPdf($id)
    {
        $reserva = Reserva::with([

            'cliente',
            'habitacion',
            'huespedes'

        ])->findOrFail($id);


        $config = ConfiguracionFiscal::first();


        $pdf = Pdf::loadView(

            'pdf.registro-huesped',

            compact(
                'reserva',
                'config'
            )

        );


        $pdf->setPaper('letter');


        return $pdf->stream(
            'registro-huesped.pdf'
        );
    }

    public function destroyExtra($pivotId)
    {
        DB::table('extra_reserva')
            ->where('id', $pivotId)
            ->delete();

        return back()->with(

            'success',

            'Extra eliminado correctamente'

        );
    }
}
