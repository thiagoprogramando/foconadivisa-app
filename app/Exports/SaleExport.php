<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromCollection, WithHeadings, WithMapping {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function collection() {

        $query = Sale::query();

        if ($this->request->has('user_id') && $this->request->user_id) {
            $query->where('user_id', $this->request->user_id);
        }

        if ($this->request->has('product_id') && $this->request->product_id) {
            $query->where('product_id', $this->request->product_id);
        }

        if ($this->request->has('payment_method') && $this->request->payment_method) {
            $query->where('payment_method', $this->request->payment_method);
        }

        if ($this->request->has('payment_status') && $this->request->payment_status) {
            $query->where('payment_status', $this->request->payment_status);
        }

        if ($this->request->has('type') && $this->request->type) {
            $query->where('type', $this->request->type);
        }

        return $query->with(['user', 'product'])->select('id', 'user_id', 'product_id', 'payment_status', 'created_at')->get();
    }

    public function headings(): array {
        return [
            'ID da Venda',
            'Cliente',
            'Produto',
            'Valor',
            'Situação do Pagamento',
            'Data de Venda',
        ];
    }

    public function map($sale): array {
        return [
            $sale->id,
            $sale->user->name ?? 'N/A',              
            $sale->product->name ?? 'N/A',           
            $this->formatValor($sale->product->value),        
            $this->formatStatus($sale->payment_status), 
            $sale->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function formatValor($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    private function formatStatus($status) {
        switch ($status) {
            case 1:
                return 'Aprovado';
            case 2:
                return 'Pendente';
            case 3:
                return 'Cancelado';
            default:
                return 'Pendente';
        }
    }
}
