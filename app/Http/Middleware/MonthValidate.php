<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Gateway\AssasController;
use App\Models\Invoice;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;

class MonthValidate {

    public function handle(Request $request, Closure $next): Response {

        if ($this->hasOverdueMonthlyInvoice() >= 1) {
            return redirect()->route('pagamentos')->with('info', 'Existem Faturas do Plano em aberto!');
        }

        if (Auth::user()->type !== 1) {

            if (!empty(Auth::user()->plan)) {
                return redirect()->route('planos')->with('info', 'Você precisa escolher um Plano!');
            }

            $planType = Auth::user()->labelPlan->type;
            switch ($planType) {
                case 1:
                    $redirect = $this->checkMonthlyInvoice();
                    if ($redirect) {
                        return $redirect;
                    }
                    break;
                case 2:
                    $redirect = $this->checkYearlyInvoice();
                    if ($redirect) {
                        return $redirect;
                    }
                    break;
            }
        }
        
        return $next($request);
    }

    private function hasOverdueMonthlyInvoice() {
        
        return Auth::user()->invoices()
            ->where('plan_id', Auth::user()->plan) 
            ->where('payment_status', 0) 
            ->where('type', 1)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->count();
    }

    private function checkMonthlyInvoice() {

        if (Auth::user()->labelPlan->value <= 0) {
            return null;
        }
       
        $lastInvoice = Auth::user()->invoices()->where('plan_id', Auth::user()->plan)->latest()->first();
        if (!$lastInvoice || Carbon::parse($lastInvoice->created_at)->lt(Carbon::now()->subDays(30))) {

            $assas = new AssasController();
            $dataInvoice = $assas->createInvoice(
                                'PIX', 
                                null, 
                                Auth::user()->customer, 
                                Auth::user()->labelPlan->value, 
                                Auth::user()->labelPlan->name
                            );
            if ($dataInvoice !== false) {
                $invoice = new Invoice();
                $invoice->user_id        = Auth::user()->id;
                $invoice->plan_id        = Auth::user()->plan;
                $invoice->value          = Auth::user()->labelPlan->value;
                $invoice->type           = 1;
                $invoice->due_date       = Carbon::now()->addDays(7);
                $invoice->payment_token  = $dataInvoice['id'];
                $invoice->payment_url    = $dataInvoice['invoiceUrl'];
                $invoice->payment_status = 0;
                $invoice->save();
            } else {
                return redirect()->route('perfil')->with('info', 'Complete seus dados para ter acesso completo aos benefícios da Plataforma!');
            }
        } 

        return null;
    }

    private function checkYearlyInvoice() {
        
        $lastInvoice = Auth::user()->invoices()->where('plan_id', Auth::user()->plan)->latest()->first();
        if (!$lastInvoice || Carbon::parse($lastInvoice->created_at)->lt(Carbon::now()->subDays(365))) {
            
            $assas = new AssasController();
            $dataInvoice = $assas->createInvoice(
                                'PIX', 
                                null, 
                                Auth::user()->customer, 
                                Auth::user()->labelPlan->value, 
                                Auth::user()->labelPlan->name
                            );
            if ($dataInvoice !== false) {
                $invoice = new Invoice();
                $invoice->user_id        = Auth::user()->id;
                $invoice->plan_id        = Auth::user()->plan;
                $invoice->value          = Auth::user()->labelPlan->value;
                $invoice->type           = 1;
                $invoice->due_date       = Carbon::now()->addDays(7);
                $invoice->payment_token  = $dataInvoice['id'];
                $invoice->payment_url    = $dataInvoice['invoiceUrl'];
                $invoice->payment_status = 0;
                $invoice->save();
            } else {
                return redirect()->route('perfil')->with('info', 'Complete seus dados para ter acesso completo aos benefícios da Plataforma!');
            }
        }

        return null;
    }
}
