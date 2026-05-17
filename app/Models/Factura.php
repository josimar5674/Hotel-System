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

        'fecha_limite',

        'subtotal',

        'impuesto_15',

        'impuesto_18',

        'impuesto_turismo',

        'total_impuestos',

        'total',

        'estado',

        'fecha_emision',

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

public function formaPago()
{
    return $this->belongsTo(FormaPago::class);
}
public function pagos()
{
    return $this->hasMany(Pago::class);
}

}