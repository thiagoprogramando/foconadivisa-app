<?php

namespace App\Http\Controllers\Data;

use App\Exports\invoiceExport;
use App\Exports\PlanExport;
use App\Exports\SaleExport;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller {
    
    public function userExcel(Request $request) {
        return Excel::download(new UserExport($request), 'Usuários.xlsx');
    }

    public function planExcel(Request $request) {
        return Excel::download(new PlanExport($request), 'Planos.xlsx');
    }

    public function invoiceExcel(Request $request) {
        return Excel::download(new invoiceExport($request), 'Faturas.xlsx');
    }

    public function saleExcel(Request $request) {
        return Excel::download(new SaleExport($request), 'Vendas.xlsx');
    }
}
