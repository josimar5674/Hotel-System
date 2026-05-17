<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionFiscal extends Model
{
    protected $table = 'configuracion_fiscal';

    protected $fillable = [

        'nombre_negocio',

        'razon_social',

        'rtn',

        'direccion',

        'telefono',

        'correo',

        'cai',

        'factura_inicio',

        'factura_fin',

        'siguiente_numero',

        'fecha_limite',

        'impuesto_15',

        'impuesto_18',

        'impuesto_turismo',
        
        'serie',

    ];
}