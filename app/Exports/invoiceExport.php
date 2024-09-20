<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class invoiceExport implements FromCollection, WithHeadings, WithMapping {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function collection() {

        $query = Invoice::query();

        if ($this->request->has('user_id') && $this->request->user_id) {
            $query->where('user_id', $this->request->user_id);
        }

        if ($this->request->has('plan_id') && $this->request->plan_id) {
            $query->where('plan_id', $this->request->plan_id);
        }

        if ($this->request->has('payment_status') && $this->request->payment_status) {
            $query->where('payment_status', $this->request->payment_status);
        }

        return $query->with('labelUser')->select('id', 'user_id', 'value', 'payment_status', 'payment_url', 'created_at')->get();
    }

    public function headings(): array {
        return [
            'ID', 
            'Nome', 
            'Valor',
            'Situação',
            'Link de Pagamento',
            'Data de Cadastro',
        ];
    }

    public function map($invoice): array {
        return [
            $invoice->id,
            $invoice->labelUser->name ?? 'N/A',
            $this->formatValor($invoice->value),
            $this->formatType($invoice->payment_status),
            $invoice->payment_url,
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function formatValor($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    private function formatType($type) {
        switch ($type) {
            case 1:
                return 'Aprovado';
            case 2:
                return 'Pendente';
            default:
                return 'Pendente';
        }
    }
}
