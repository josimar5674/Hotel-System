<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $habitaciones = Habitacion::all();

    return view('habitaciones.index', compact('habitaciones'));
}

    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    return view('habitaciones.create');
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    Habitacion::create([
        'numero' => $request->numero,
        'tipo' => $request->tipo,
        'precio' => $request->precio,
        'estado' => $request->estado,
        'descripcion' => $request->descripcion,
    ]);

    return redirect()
        ->route('habitaciones.index')
        ->with('success', 'Habitación creada correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show(Habitacion $habitacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
  public function edit(Habitacion $habitacione)
{
    return view('habitaciones.edit', [
        'habitacion' => $habitacione
    ]);
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Habitacion $habitacione)
{
    $habitacione->update([

        'numero' => $request->numero,
        'tipo' => $request->tipo,
        'precio' => $request->precio,
        'estado' => $request->estado,
        'descripcion' => $request->descripcion,

    ]);

    return redirect()
        ->route('habitaciones.index')
        ->with('success', 'Habitación actualizada');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habitacion $habitacion)
    {
        //
    }
}
