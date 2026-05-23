<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $fillable = [

    'nombre',

    'tipo',

    'valor',

    'activo'

];
}
