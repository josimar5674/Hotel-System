<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [

        'factura_id',

        'forma_pago_id',

        'monto',

        'referencia'

    ];


    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }


    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class);
    }
}