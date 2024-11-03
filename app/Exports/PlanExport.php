<?php

namespace App\Exports;

use App\Models\Plan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlanExport implements FromCollection, WithHeadings, WithMapping {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function collection() {

        $query = Plan::query();

        if ($this->request->has('name') && $this->request->name) {
            $query->where('name', 'like', '%' . $this->request->name . '%');
        }

        if ($this->request->has('email') && $this->request->email) {
            $query->where('email', 'like', '%' . $this->request->email . '%');
        }

        if ($this->request->has('cpfcnpj') && $this->request->cpfcnpj) {
            $query->where('cpfcnpj', 'like', '%' . $this->request->cpfcnpj . '%');
        }

        return $query->select('id', 'name', 'description', 'value', 'type', 'created_at')->get();

    }

    public function headings(): array {
        return [
            'ID', 
            'Nome', 
            'Descrição',
            'Valor',
            'Tipo', 
            'Data de Cadastro',
        ];
    }

    public function map($plan): array {
        return [
            $plan->id,
            $plan->name,
            $plan->description,
            $this->formatValor($plan->value),
            $this->formatType($plan->type),
            $plan->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function formatValor($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    private function formatType($type) {
        switch ($type) {
            case 1:
                return 'Mês';
            case 2:
                return 'Ano';
            case 3:
                return 'Vitalício';
            default:
                return '---';
        }
    }
}
