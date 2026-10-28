<?php
namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AnulacionFacturaController extends Controller
{
    /**
     * Anular una factura conservando su registro.
     *
     * Solo los usuarios autorizados por el middleware
     * admin pueden acceder a esta operación.
     */
    public function anular(Request $request, Factura $factura)
    {
        $request->validate([
            'motivo_anulacion' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);
        DB::transaction(function () use ($request, $factura) {
            // Bloquear el registro para evitar anulaciones simultáneas.
            $factura = Factura::whereKey($factura->id)
                ->lockForUpdate()
                ->firstOrFail();
            // Solo se pueden anular facturas emitidas.
            if ($factura->estado !== 'emitida') {
                abort(
                    422,
                    'Esta factura ya fue anulada o no se encuentra emitida.'
                );
            }
            // Registrar la anulación sin eliminar la factura.
            $factura->update([
                'estado' => 'anulada',
                'anulada_por' => Auth::id(),
                'fecha_anulacion' => now(),
                'motivo_anulacion' => $request->motivo_anulacion,
            ]);
        });
        return redirect()
            ->back()
            ->with(
                'success',
                'La factura fue anulada correctamente. Su registro se conserva en el sistema.'
            );
    }
    /**
     * Autorizar la reutilización de un número de factura.
     *
     * Este método se implementará cuando definamos
     * el registro y la validación de las autorizaciones.
     */
    public function autorizarReutilizacion(
        Request $request,
        Factura $factura
    ) {
        abort(
            501,
            'La autorización de reutilización de números todavía no está implementada.'
        );
    }
}