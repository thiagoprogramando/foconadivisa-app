<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller {
    
    public function invoices(Request $request) {

        $query = Invoice::orderBy('payment_status', 'asc');

        if(!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if(!empty($request->plan_id)) {
            $query->where('plan_id', $request->plan_id);
        }

        if(!empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }

        $invoices = $query->get();

        return view('app.Sale.list-invoice', [
            'invoices' => $invoices,
            'users'    => User::all(),
            'plans'    => Plan::all()
        ]);
    }

    public function confirmPayment($id) {

        $invoice = Invoice::find($id);
        if(!$invoice) {
            return redirect()->back()->with('error', 'Nenhuma Fatura foi encontrada!');
        }

        $user       = User::find($invoice->user_id);
        $user->plan = $invoice->plan_id;
        $user->save();

        $invoice->payment_status = 1;
        if($invoice->save()) {
            $notification               = new Notification();
            $notification->user_id      = $user->id;
            $notification->type         = 2;
            $notification->title        = 'Pagamento aprovado!';
            $notification->description  = 'Agradecemos por nos escolher. Aproveite os benefícios do seu novo Plano!';
            $notification->save();

            return redirect()->back()->with('success', 'Fatura confirmada com sucesso!');
        }

        return redirect()->back()->with('error', 'Nenhuma Fatura foi encontrada!');
    }

    public function deletePayment(Request $request) {

        $invoice = Invoice::find($request->id);
        if($invoice && $invoice->delete()) {
            return redirect()->back()->with('success', 'Fatura excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Nenhuma Fatura foi encontrada!');
    }

}
