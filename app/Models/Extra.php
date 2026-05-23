<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    protected $fillable = [

        'nombre',

        'precio',

        'activo'

    ];

    public function reservas()
    {
        return $this->belongsToMany(
            Reserva::class
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
}