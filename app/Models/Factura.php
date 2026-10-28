<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\User;

class Factura extends Model
{
    protected $fillable = [

        'reserva_id',

        'cliente_id',

        'usuario_id',

        'numero_factura',

        'cai',

        'rango_autorizado',

        'fecha_limite',
        
        'subtotal',

        'impuesto_15',

        'impuesto_18',

        'impuesto_turismo',

        'total_impuestos',

        'total',

        'estado',

        'fecha_emision',
        'anulada_por',
        'fecha_anulacion',
        'motivo_anulacion',

    ];
   protected $casts = [
    'fecha_emision' => 'datetime',
    'fecha_limite' => 'date',
    'fecha_anulacion' => 'datetime',
    'subtotal' => 'decimal:2',
    'impuesto_15' => 'decimal:2',
    'impuesto_18' => 'decimal:2',
    'impuesto_turismo' => 'decimal:2',
    'total_impuestos' => 'decimal:2',
    'total' => 'decimal:2',
];

    public function cliente()
{
    return $this->belongsTo(Cliente::class);
}

public function reserva()
{
    return $this->belongsTo(Reserva::class);
}

public function usuario()
{
    return $this->belongsTo(
        User::class,
        'usuario_id'
    );
}


public function pagos()
{
    return $this->hasMany(Pago::class);
}

public function administradorAnulacion()
{
    return $this->belongsTo(
        User::class,
        'anulada_por'
    );
}

}