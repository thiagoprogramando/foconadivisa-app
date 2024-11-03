<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping {

    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }
   
    public function collection() {

        $query = User::query();

        if ($this->request->has('name') && $this->request->name) {
            $query->where('name', 'like', '%' . $this->request->name . '%');
        }

        if ($this->request->has('email') && $this->request->email) {
            $query->where('email', 'like', '%' . $this->request->email . '%');
        }

        if ($this->request->has('cpfcnpj') && $this->request->cpfcnpj) {
            $query->where('cpfcnpj', 'like', '%' . $this->request->cpfcnpj . '%');
        }

        return $query->select('id', 'name', 'cpfcnpj', 'phone', 'email', 'created_at')->get();
    }

    public function headings(): array {
        return [
            'ID', 
            'Nome', 
            'CPF/CNPJ',
            'Telefone',
            'Email', 
            'Data de Cadastro',
        ];
    }

    public function map($user): array {
        return [
            $user->id,
            $user->name,
            $this->formatCpfCnpj($user->cpfcnpj),
            $this->formatPhone($user->phone),
            $user->email,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function formatCpfCnpj($value) {

        $cpfCnpj = preg_replace('/\D/', '', $value);
        if (strlen($cpfCnpj) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfCnpj);
        }

        if (strlen($cpfCnpj) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cpfCnpj);
        }

        return $value;
    }

    private function formatPhone($phone) {
        
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        }

        if (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }

        if (strlen($phone) === 9) {
            return preg_replace('/(\d{5})(\d{4})/', '$1-$2', $phone);
        }

        if (strlen($phone) === 8) {
            return preg_replace('/(\d{4})(\d{4})/', '$1-$2', $phone);
        }

        return $phone;
    }
}
