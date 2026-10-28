<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class ReporteContableExport implements Export, WithMultipleSheets
{
    protected $desde;
    protected $hasta;
    public function __construct($desde, $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }
    public function sheets(): array
    {
        return [
            new FacturasSheet($this->desde, $this->hasta),
            new ResumenImpuestosSheet($this->desde, $this->hasta),
        ];
    }
}