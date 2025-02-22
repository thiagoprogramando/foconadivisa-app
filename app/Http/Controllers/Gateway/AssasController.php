<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Mail\Product;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;

use GuzzleHttp\Client;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AssasController extends Controller {
    
    public function payPlan($id) {

        if (empty(Auth::user()->cpfcnpj) || empty(Auth::user()->phone)) {
            return redirect()->route('perfil')->with('error', 'Ops! Complete o seu cadastro antes de adquirir um plano.');
        }

        $customer = Auth::user()->customer ?? $this->createCustomer(Auth::user()->id);
        if (!empty($customer['status']) && ($customer['status'] == false || $customer['status'] == 0)) {
            return redirect()->back()->with('error', $customer['message']);
        }

        $plan = Plan::find($id);
        if (!$plan) {
            return redirect()->back()->with('error', 'Ops! O plano não está disponível.');
        }

        if ($plan->value <= 0) {

            $user = User::find(Auth::id());
            $user->plan = $plan->id;
            if ($user->save()) {
                return redirect()->back()->with('success', 'Plano alterado com sucesso!');
            }

            return redirect()->back()->with('error', 'Erro ao salvar o plano gratuito.');
        }

        return redirect()->back()->with('error', 'Ops! Algo deu errado. Verifique seus dados e tente novamente!');
    }

    public function buyPlan(Request $request) {

        if (empty(Auth::user()->cpfcnpj) || empty(Auth::user()->phone)) {
            return redirect()->route('perfil')->with('error', 'Ops! Complete o seu cadastro antes de adquirir um plano.');
        }

        if (empty($request->method) || empty($request->installments)) {
            return redirect()->back()->with('info', 'É necessário escolher uma Forma de Pagamento!');
        }

        $customer = Auth::user()->customer ?? $this->createCustomer(Auth::user()->id);
        if (!empty($customer['status']) && ($customer['status'] == false || $customer['status'] == 0)) {
            return redirect()->back()->with('error', $customer['message']);
        }

        $plan = Plan::find($request->id);
        if (!$plan) {
            return redirect()->back()->with('error', 'Ops! O plano não está disponível.');
        }

        if ($plan->value <= 0) {

            $user = User::find(Auth::id());
            $user->plan = $plan->id;
            if ($user->save()) {
                return redirect()->back()->with('success', 'Plano alterado com sucesso!');
            }

            return redirect()->back()->with('error', 'Erro ao salvar o plano gratuito.');
        }

        if (Auth::user()->hasUsedTrial()) {
            $due_date = now();
        } else {
            $due_date = now()->addDay(env('TIME_DUE_DATE'));
            DB::table('trial_histories')->insert([
                'user_id'       => Auth::user()->id,
                'plan_id'       => $plan->id,
                'start_date'    => now(),
                'end_date'      => now()->addDays(env('TIME_DUE_DATE')),
                'created_at'    => now(),
            ]);
        }

        $dataInvoice = $this->createInvoice($request->method, $request->installments, $customer, $plan->value, $plan->name, $due_date);
        if (!$dataInvoice) {
            return redirect()->back()->with('error', 'Não foi possível gerar sua fatura! Verifique seus dados e tente novamente.');
        }

        Invoice::where('user_id', Auth::user()->id)
            ->where('payment_status', 0)
            ->delete();

        $invoice                = new Invoice();
        $invoice->user_id       = Auth::user()->id;
        $invoice->plan_id       = $plan->id;
        $invoice->value         = $plan->value;
        $invoice->type          = 1;
        $invoice->due_date      = $due_date;
        $invoice->payment_token = $dataInvoice['id'];
        $invoice->payment_url   = $dataInvoice['invoiceUrl'];

        if ($invoice->save()) {

            $notification               = new Notification();
            $notification->user_id      = Auth::user()->id;
            $notification->type         = 1;
            $notification->title        = 'Fatura gerada para o novo Plano!';
            $notification->description  = 'Sua fatura já está disponível para pagamento, encontre-a na página de pendências!';
            $notification->url          = env('APP_URL').'pagamentos';
            $notification->save();

            $user       = User::find(Auth::id());
            $user->plan = $plan->id;
            if ($user->save()) {
                return redirect()->back()->with('success', 'Plano alterado com sucesso! Aproveite os benefícios.');
            }

            return redirect()->back()->with('error', 'Ops! Algo deu errado. Verifique seus dados e tente novamente!');
        }

        return redirect()->back()->with('error', 'Ops! Algo deu errado. Verifique seus dados e tente novamente!');
    }

    public function createCustomer($id) {

        $user = User::find($id);
        if (!$user) {
            return [
                'status'  => false,
                'message' => 'Não foi possível encontrar dados do Usuário!'
            ];
        }

        try {
            $client = new Client();

            $options = [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'access_token'  => env('API_KEY'),
                    'User-Agent'    => env('APP_NAME')
                ],
                'json' => [
                    'name'                  => $user->name,
                    'cpfCnpj'               => $user->cpfcnpj,
                    'mobilePhone'           => $user->phone,
                    'email'                 => $user->email,
                    'notificationDisabled'  => true
                ],
                'verify' => false
            ];

            $response = $client->post(env('API_URL_ASSAS') . 'v3/customers', $options);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $user->customer = $data['id'];
                $user->save();
                return [
                    'status' => true,
                    'id'     => $data['id']
                ];
            }
    
            $errorMessage = 'Erro desconhecido.';
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
                $errorBody = json_decode($response->getBody(), true);
                if (isset($errorBody['errors'][0]['description'])) {
                    $errorMessage = $errorBody['errors'][0]['description'];
                } else {
                    $errorMessage = isset($errorBody['message']) ? $errorBody['message'] : 'Estamos com problemas no momento, tente novamente mais tarde!';
                }
            } elseif ($response->getStatusCode() >= 500) {
                $errorMessage = 'Estamos com problemas no momento, tente novamente mais tarde!';
            }
    
            return [
                'status'  => false,
                'message' => $errorMessage
            ];
        } catch (\Exception $e) {
            
            $errorMessage = $e->getMessage();
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $errorBody = json_decode($response->getBody(), true);
                if (isset($errorBody['errors'][0]['description'])) {
                    $errorMessage = $errorBody['errors'][0]['description'];
                } elseif (isset($errorBody['message'])) {
                    $errorMessage = $errorBody['message'];
                }
            }

            return [
                'status'  => false,
                'message' => $errorMessage
            ];
        }
    }

    public function createInvoice($method, $installments, $customer, $value, $description, $due_date = null) {
        try {
            $client = new Client();
    
            $options = [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'access_token'  => env('API_KEY'),
                    'User-Agent'    => env('APP_NAME'),
                ],
                'json' => [
                    'customer'          => $customer,
                    'billingType'       => $method ?? 'UNDEFINED',
                    'value'             => number_format($value, 2, '.', ''),
                    'dueDate'           => $due_date ?? now()->addDay(1)->toDateString(),
                    'description'       => $description,
                    'installmentCount'  => $installments,
                    'installmentValue'  => $installments > 0 ? number_format(($value / $installments), 2, '.', '') : $value,
                ],
                'verify' => false,
            ];
    
            $response = $client->post(env('API_URL_ASSAS') . 'v3/payments', $options);
    
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return [
                    'id' => $data['id'],
                    'invoiceUrl' => $data['invoiceUrl'],
                ];
            }
        } catch (\Exception $e) {
            Log::error('Erro Ao Criar Invoice: ' . $e->getMessage());
            return false;
        }
    }    

    public function webhook(Request $request) {

        $jsonData = $request->json()->all();
        $token = $jsonData['payment']['id'];

        if ($jsonData['event'] === 'PAYMENT_CONFIRMED' || $jsonData['event'] === 'PAYMENT_RECEIVED') {

            $invoice = Invoice::where('payment_token', $token)->first();
            if($invoice) {

                $user = User::find($invoice->user_id);
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
                }

                return response()->json(['status' => 'success', 'message' => 'Processo concluído com sucesso!']);
            }
            
            $sale = Sale::where('payment_token', $token)->first();
            if($sale) {

                $url = env('APP_URL').'login';
                
                $send = Mail::to($sale->user->email, $sale->user->name)->send(new Product([
                    'toName'    => $sale->user->name,
                    'toEmail'   => $sale->user->email,
                    'fromName'  => env('MAIL_FROM_NAME'),
                    'fromEmail' => env('MAIL_FROM_ADDRESS'),
                    'subject'   => 'Seu pedido chegou✅',
                    'message'   => "<b>Você comprou e chegou rapidinho!</b> Segue abaixo o produto: {$sale->product->name}. Obrigado pela compra! Para acessar o seu conteúdo/material, siga os seguintes passos: <br>
                                    1 - <a href='{$url}'>Acesse nosso site</a> <br>
                                    2 - Informe seu Email e senha (sua senha sempre será o CPF ou CNPJ informado na hora da compra) <br>
                                    3 - vá até <b>Minhas Compras</b> <br>
                                    4 - Aproveite o Produto <br>"
                ]));

                if($send) {
                    $sale->delivery = 1;
                    $sale->payment_status = 1;
                    $sale->save();

                    return response()->json(['status' => 'success', 'message' => 'Venda recebida e Produto enviado!']);
                }

                return response()->json(['status' => 'error', 'message' => 'Produto não enviado!']);
            }
            
            return response()->json(['status' => 'success', 'message' => 'Nenhuma fatura encontrada!']);
        }

        if ($jsonData['event'] === 'PAYMENT_OVERDUE') {

            $invoice = Invoice::where('payment_token', $token)->first();
            if ($invoice) {


                $user = User::find($invoice->user_id);
                if ($user) {
                    $user->plan = null;
                    $user->save();

                    Invoice::where('user_id', $user->id)
                        ->where('payment_status', 0)
                        ->delete();

                    $notification               = new Notification();
                    $notification->user_id      = $user->id;
                    $notification->type         = 3;
                    $notification->title        = 'Plano cancelado!';
                    $notification->description  = 'Por falta de pagamento, cancelamos o seu plano. Você pode assinar outro!';
                    $notification->url          = env('APP_URL').'planos';
                    $notification->save();

                    return response()->json(['status' => 'success', 'message' => 'Plano cancelado para o usuário!']);
                }

                return response()->json(['status' => 'success', 'message' => 'Nenhum usuário associado a essa Fatura!']);
            }

            return response()->json(['status' => 'success', 'message' => 'Webhook não utilizado!']);
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook não utilizado!']);
    }
}
