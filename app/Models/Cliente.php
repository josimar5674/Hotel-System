<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

protected $fillable = [

    'nombre',
    'identidad',
    'rtn',
    'telefono',
    'correo',
    'direccion',
    'nacionalidad',
    'pais_procedencia',
    'genero',
    'fecha_nacimiento',

];
}
