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
        'cantidad',
        'precio'
    ])
    ->withTimestamps();
}
}