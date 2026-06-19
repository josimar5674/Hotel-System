<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ConfiguracionFiscalController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\CierreDiarioController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if(Auth::check()) {

        return redirect()->route(
            'reservas.index'
        );
    }

    return redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return redirect()->route(
        'reservas.index'
    );

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'clientes',
        ClienteController::class
    );


    /*
    |--------------------------------------------------------------------------
    | RESERVAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'reservas',
        ReservaController::class
    );

    Route::post(
        'reservas/{reserva}/checkin',
        [ReservaController::class, 'checkin']
    )->name('reservas.checkin');

    Route::post(
        'reservas/{reserva}/checkout',
        [ReservaController::class, 'checkout']
    )->name('reservas.checkout');

    Route::get(
        '/reservas/{reserva}/extras',
        [ReservaController::class, 'extras']
    )->name('reservas.extras');

    Route::post(
        '/reservas/{reserva}/extras',
        [ReservaController::class, 'storeExtra']
    )->name('reservas.extras.store');


    /*
    |--------------------------------------------------------------------------
    | FACTURAS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/facturas/{factura}/pdf',
        [FacturaController::class, 'pdf']
    )->name('facturas.pdf');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HABITACIONES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'habitaciones',
        HabitacionController::class
    );


    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/configuracion-fiscal',
        [ConfiguracionFiscalController::class, 'edit']
    )->name('configuracion-fiscal.edit');

    Route::put(
        '/configuracion-fiscal',
        [ConfiguracionFiscalController::class, 'update']
    )->name('configuracion-fiscal.update');


    /*
    |--------------------------------------------------------------------------
    | FORMAS PAGO
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/formas-pago',
        [FormaPagoController::class, 'store']
    )->name('formas-pago.store');

    Route::put(
        '/formas-pago/{formaPago}',
        [FormaPagoController::class, 'update']
    )->name('formas-pago.update');

    Route::post(
        '/formas-pago/{formaPago}/toggle',
        [FormaPagoController::class, 'toggle']
    )->name('formas-pago.toggle');


    /*
    |--------------------------------------------------------------------------
    | EXTRAS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/extras',
        [ExtraController::class, 'store']
    )->name('extras.store');

    Route::put(
        '/extras/{extra}',
        [ExtraController::class, 'update']
    )->name('extras.update');

    Route::post(
        '/extras/{extra}/toggle',
        [ExtraController::class, 'toggle']
    )->name('extras.toggle');


    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'usuarios',
        UsuarioController::class
    );

    // DESCUENTOS

Route::post(
    '/descuentos',
    [DescuentoController::class, 'store']
)->name('descuentos.store');


Route::put(
    '/descuentos/{descuento}',
    [DescuentoController::class, 'update']
)->name('descuentos.update');


Route::post(
    '/descuentos/{descuento}/toggle',
    [DescuentoController::class, 'toggle']
)->name('descuentos.toggle');


Route::delete(
    '/reservas/extras/{pivotId}',
    [ReservaController::class, 'destroyExtra']
)->name('reservas.extras.destroy');

});

Route::get(
    '/reservas/{reserva}/registro-huesped',
    [ReservaController::class, 'registroHuespedPdf']
)->name('reservas.registro-huesped');


Route::middleware(['auth'])->group(function () {

    Route::resource(
        'cierres',
        CierreDiarioController::class
    )->only([
        'index',
        'create',
        'store',
        'show'
    ]);

});


require __DIR__.'/auth.php';


