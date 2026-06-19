<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreDiario extends Model
{
    protected $table = 'cierres_diarios';

    protected $fillable = [

        'fecha',

        'usuario_id',

        'total_facturado',

        'efectivo_facturado',

        'tarjeta_facturado',

        'transferencia_facturado',

        'efectivo_contado',

        'diferencia',

        'cantidad_facturas',

        'observaciones',

        'fecha_cierre',
        
        'detalle_pagos', 

    ];

protected $casts = [

    'fecha' => 'date',

    'fecha_cierre' => 'datetime',

    'detalle_pagos' => 'array',

];

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}