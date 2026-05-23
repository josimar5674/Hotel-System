<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ReservaHuesped;


class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [

        'cliente_id',
        'habitacion_id',
        'fecha_entrada',
        'fecha_salida',
        'cantidad_personas',
        'estado',
        'observaciones',
        'fecha_checkin',
        'fecha_checkout',
        'usuario_checkin_id',
        'usuario_checkout_id',
        'huespedes',

    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function usuarioCheckin()
    {
        return $this->belongsTo(
            User::class,
            'usuario_checkin_id'
        );
    }

    public function usuarioCheckout()
    {
        return $this->belongsTo(
            User::class,
            'usuario_checkout_id'
        );
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

public function extras()
{
    return $this->belongsToMany(
        Extra::class
    )
    ->withPivot([

        'id',

        'cantidad',

        'precio',

        'descuento_id',

        'descuento_monto'

    ])
    ->withTimestamps();
}


public function huespedes()
{
    return $this->hasMany(
        ReservaHuesped::class,
        'reserva_id'
    );
}


}