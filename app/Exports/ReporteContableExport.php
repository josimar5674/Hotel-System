<?php

namespace App\Exports;

use App\Models\Factura;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteContableExport implements FromCollection, WithHeadings
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
        return Factura::query()
            ->where('estado', 'emitida')
            ->whereBetween('fecha_emision', [
                \Carbon\Carbon::parse($this->desde)->startOfDay(),
                \Carbon\Carbon::parse($this->hasta)->endOfDay(),
            ])
            ->orderBy('fecha_emision', 'desc')
            ->get([
                'id',
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
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Reserva ID',
            'Cliente ID',
            'Usuario ID',
            'Número de factura',
            'CAI',
            'Fecha límite',
            'Subtotal',
            'Impuesto 15%',
            'Impuesto 18%',
            'Impuesto turismo',
            'Total impuestos',
            'Total',
            'Estado',
            'Fecha emisión',
        ];
    }
}