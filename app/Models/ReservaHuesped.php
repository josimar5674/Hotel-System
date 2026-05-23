<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaHuesped extends Model
{
    protected $table = 'reserva_huespedes';

    protected $fillable = [

        'reserva_id',
        'nombre',
        'identidad',
        'nacionalidad',
        'pais_procedencia',
        'genero',
        'fecha_nacimiento'

    ];
}