<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $fillable = [

        'nombre',

        'activo'

    ];

    public function pagos()
{
    return $this->hasMany(Pago::class);
}
}