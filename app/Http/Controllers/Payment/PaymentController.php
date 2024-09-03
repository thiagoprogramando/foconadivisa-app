<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller {
    
    public function payments() {

        $payments = Invoice::where('user_id', Auth::user()->id)->orderBy('payment_status', 'asc')->paginate(10);
        return view('app.Payment.list-payment', [
            'payments' => $payments
        ]);
    }

    public function deletePayment(Request $request) {
        
        $payment = Invoice::find($request->id);

        if($payment->payment_status == 1) {
            return redirect()->back()->with('error', 'Ops! O pagamento dessa Fatura já foi aprovado.');
        }

        if($payment && $payment->delete()) {

            return redirect()->back()->with('success', 'Fatura excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

}
