<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\User;

use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssasController extends Controller {
    
    public function payPlan($id) {

        if(empty(Auth::user()->cpfcnpj) || empty(Auth::user()->phone)) {
            return redirect()->route('perfil')->with('error', 'Ops! Complete o seu cadastro antes de adquirir um plano.');
        }

        if(!empty(Auth::user()->customer)) {
            $customer = Auth::user()->customer;
        } else {
            $customer = $this->createCustomer(Auth::user()->id);
        }

        $plan = Plan::find($id);
        if(!$plan) {
            return redirect()->back()->with('error', 'Ops! O plano não está disponível.');
        }

        $dataInvoice = $this->createInvoice($customer, $plan->value, $plan->name);
        if($dataInvoice == null) {
            return redirect()->back()->with('error', 'Ops! Algo de errado. Revise seus dados e tente novamente!');
        }

        Invoice::where('user_id', Auth::user()->id)
        ->where('payment_status', 0)
        ->where('plan_id', $plan->id)
        ->delete();

        $invoice                = new Invoice();
        $invoice->user_id       = Auth::user()->id;
        $invoice->plan_id       = $plan->id;
        $invoice->value         = $plan->value;
        $invoice->payment_token = $dataInvoice['id'];
        $invoice->payment_url   = $dataInvoice['invoiceUrl'];
        if($invoice->save()) {

            $notification               = new Notification();
            $notification->user_id      = Auth::user()->id;
            $notification->type         = 1;
            $notification->title        = 'Fatura gerada para o novo Plano!';
            $notification->description  = 'Sua fatura já está disponível para pagamento, encontre-a na página de pendências!';
            $notification->save();

            return redirect($dataInvoice['invoiceUrl']);
        }

        return redirect()->back()->with('error', 'Ops! Algo de errado. Revise seus dados e tente novamente!');
    }

    private function createCustomer($id) {

        $user = User::find($id);
        if(!$user) {
            return false;
        }

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('API_KEY'),
                'User-Agent'   => env('APP_NAME')
            ],
            'json' => [
                'name'          => $user->name,
                'cpfCnpj'       => $user->cpfcnpj,
                'mobilePhone'   => $user->phone,
                'email'         => $user->email,
                'notificationDisabled' => true
            ],
            'verify' => false
        ];

        $response = $client->post(env('API_URL_ASSAS') . 'v3/customers', $options);
        $body = (string) $response->getBody();
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);

            $user->customer = $data['id'];
            if($user->save()) {
                return $data['id'];
            }

            return $data['id'];
        } else {
            return false;
        }
    }

    private function createInvoice($customer, $value, $description) {
        	
        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('API_KEY'),
                'User-Agent'   => env('APP_NAME')
            ],
            'json' => [
                'customer'          => $customer,
                'billingType'       => 'UNDEFINED',
                'value'             => number_format($value, 2, '.', ''),
                'dueDate'           => now()->addDay(),
                'description'       => $description,
                // 'callback'          => [
                //     'successUrl'    => env('APP_URL'),
                //     'autoRedirect'  => true
                // ]
            ],
            'verify' => false
        ];

        $response = $client->post(env('API_URL_ASSAS') . 'v3/payments', $options);
        $body = (string) $response->getBody();

        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);
            return $dados['json'] = [
                'id'            => $data['id'],
                'invoiceUrl'    => $data['invoiceUrl'],
            ];
        } else {
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
            
            return response()->json(['status' => 'success', 'message' => 'Nenhuma fatura encontrada!']);
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook não utilizado!']);
    }
}
