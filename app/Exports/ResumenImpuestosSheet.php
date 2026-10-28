<?php
namespace App\Exports;
use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
class ResumenImpuestosSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $desde;
    protected $hasta;
    public function __construct($desde, $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }
    public function collection(): Collection
    {
        $facturas = Factura::query()
            ->where('estado', 'emitida')
            ->whereBetween('fecha_emision', [
                Carbon::parse($this->desde)->startOfDay(),
                Carbon::parse($this->hasta)->endOfDay(),
            ])
            ->get([
                'subtotal',
                'impuesto_15',
                'impuesto_18',
                'impuesto_turismo',
                'total',
            ]);
        // Bases imponibles
        $baseGeneral = 0;
        $base15 = 0;
        $base18 = 0;
        // Impuestos acumulados
        $impuesto15 = 0;
        $impuesto18 = 0;
        $impuestoTurismo = 0;
        // Total facturado
        $totalFacturado = 0;
        foreach ($facturas as $factura) {
            $subtotal = (float) $factura->subtotal;
            $i15 = (float) $factura->impuesto_15;
            $i18 = (float) $factura->impuesto_18;
            $turismo = (float) $factura->impuesto_turismo;
            // Acumular la base general una sola vez.
            $baseGeneral += $subtotal;
            // Acumular impuestos.
            $impuesto15 += $i15;
            $impuesto18 += $i18;
            $impuestoTurismo += $turismo;
            // Bases gravadas estimadas a partir del impuesto registrado.
            if ($i15 > 0) {
                $base15 += $i15 / 0.15;
            }
            if ($i18 > 0) {
                $base18 += $i18 / 0.18;
            }
            // Acumular el total real de cada factura.
            $totalFacturado += (float) $factura->total;
        }
        $totalImpuestos = (
            $impuesto15 +
            $impuesto18 +
            $impuestoTurismo
        );
        return collect([
            [
                'tipo' => 'ISV 15%',
                'base' => round($base15, 2),
                'impuesto' => round($impuesto15, 2),
                'total' => round($base15 + $impuesto15, 2),
            ],
            [
                'tipo' => 'ISV 18%',
                'base' => round($base18, 2),
                'impuesto' => round($impuesto18, 2),
                'total' => round($base18 + $impuesto18, 2),
            ],
            [
                'tipo' => 'Impuesto de turismo',
                'base' => round($baseGeneral, 2),
                'impuesto' => round($impuestoTurismo, 2),
                'total' => round(
                    $baseGeneral + $impuestoTurismo,
                    2
                ),
            ],
            [
                'tipo' => 'TOTAL GENERAL',
                'base' => round($baseGeneral, 2),
                'impuesto' => round($totalImpuestos, 2),
                'total' => round($totalFacturado, 2),
            ],
        ]);
    }
    public function headings(): array
    {
        return [
            'Tipo de impuesto',
            'Base imponible',
            'Impuesto',
            'Total',
        ];
    }
    public function title(): string
    {
        return 'Resumen de impuestos';
    }
}