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

    foreach($reservasActivas as $reserva) {

        $entrada = Carbon::parse(
            $reserva->fecha_entrada
        );

        $salida = Carbon::parse(
            $reserva->fecha_salida
        );

        $noches = $entrada->diffInDays($salida);


        // Subtotal hospedaje

        $subtotal = (
            $noches *
            $reserva->habitacion->precio
        );


        // Extras

        $totalExtras = 0;

        foreach($reserva->extras as $extra) {

            $totalExtras += (

                $extra->pivot->cantidad *

                $extra->pivot->precio

            );

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


    return view('reservas.index', compact(

        'reservasActivas',

        'reservasHistorial',

        'formasPago'

    ));
}
    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    $clientes = Cliente::all();

$habitaciones = Habitacion::whereNotIn('estado', [
    'mantenimiento'
])->get();

    return view('reservas.create', compact(
        'clientes',
        'habitaciones'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validaciones básicas

    $request->validate([

        'cliente_id' => 'required',

        'habitacion_id' => 'required',

        'fecha_entrada' => 'required|date',

        'fecha_salida' => 'required|date|after:fecha_entrada',

        'cantidad_personas' => 'required|integer|min:1',
        

    ]);


    // Validar conflicto de fechas

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


    // Si hay conflicto

    if ($reservaExistente) {

        return back()

            ->withInput()

            ->with(
                'error',
                'La habitación ya está reservada en esas fechas'
            );
    }


    // Crear reserva

    Reserva::create([

        'cliente_id' => $request->cliente_id,

        'habitacion_id' => $request->habitacion_id,

        'fecha_entrada' => $request->fecha_entrada,

        'fecha_salida' => $request->fecha_salida,

        'cantidad_personas' => $request->cantidad_personas,

        'estado' => 'reservada',

        'observaciones' => $request->observaciones,

        

    ]);


    return redirect()

        ->route('reservas.index')

        ->with(
            'success',
            'Reserva creada correctamente'
        );
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
    if($reserva->estado == 'checkin') {

        abort(403);

    }

    $clientes = Cliente::all();

    $habitaciones = Habitacion::all();

    return view('reservas.edit', compact(
        'reserva',
        'clientes',
        'habitaciones'
    ));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Reserva $reserva)
{
    // Validaciones básicas

    $request->validate([

        'cliente_id' => 'required',

        'habitacion_id' => 'required',

        'fecha_entrada' => 'required|date',

        'fecha_salida' => 'required|date|after:fecha_entrada',

        'cantidad_personas' => 'required|integer|min:1',

    ]);


    // Validar conflicto de fechas
    // IGNORANDO esta misma reserva

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


    // Si hay conflicto

    if ($reservaExistente) {

        return back()

            ->withInput()

            ->with(
                'error',
                'La habitación ya está reservada en esas fechas'
            );
    }


    // Actualizar reserva

    $reserva->update([

        'cliente_id' => $request->cliente_id,

        'habitacion_id' => $request->habitacion_id,

        'fecha_entrada' => $request->fecha_entrada,

        'fecha_salida' => $request->fecha_salida,

        'cantidad_personas' => $request->cantidad_personas,

        'observaciones' => $request->observaciones,

    ]);


    return redirect()

        ->route('reservas.index')

        ->with(
            'success',
            'Reserva actualizada'
        );
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Reserva $reserva)
{
    if($reserva->estado == 'checkin') {

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

    return view(
        'reservas.extras',
        compact(
            'reserva',
            'extras'
        )
    );
}

public function storeExtra(
    Request $request,
    Reserva $reserva
)
{
    $extra = Extra::findOrFail(
        $request->extra_id
    );


    $reserva->extras()->attach(

        $extra->id,

        [

            'cantidad' => $request->cantidad,

            'precio' => $extra->precio

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

     // Subtotal habitación

$subtotal = (
    $noches *
    $reserva->habitacion->precio
);


// Extras

$totalExtras = 0;

foreach($reserva->extras as $extra) {

    $totalExtras += (

        $extra->pivot->cantidad *

        $extra->pivot->precio

    );

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


        $total = (
            $subtotal +
            $totalImpuestos
        );


$totalPagos = collect(
    request('monto')
)->sum();

if(abs($totalPagos - $total) > 0.01) {

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


foreach(request('forma_pago_id') as $index => $formaPagoId) {

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

}