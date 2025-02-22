<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\AssasController;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;

use Illuminate\Http\Request;

class EcommerceController extends Controller {
    
    public function ecommerce(Request $request) {

        $query = Product::orderBy('name', 'asc');

        if(!empty($request->search)) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $products = $query->get();

        return view('ecommerce', [
            'products' => $products
        ]);
    }

    public function product($id) {

        $product = Product::find($id);
        if(!$product) {
            return redirect()->back()->with('error', 'Ops! Produto não disponível!');
        }

        return view('product', [
            'product' => $product
        ]);
    }

    public function order($id) {

        $product = Product::find($id);
        if(!$product) {
            return redirect()->back()->with('info', 'Ops, Produto não encontrado ou disponível!');
        }

        $paymentMethods = $product->payments;
        $installmentsOptions = $paymentMethods->mapWithKeys(function ($payment) {
            return [$payment->method => $payment->installments];
        });

        return view('order', compact('product', 'paymentMethods', 'installmentsOptions'));
    }

    public function payProduct(Request $request) {

        if (empty($request->method) || empty($request->installments)) {
            return redirect()->back()->with('info', 'É preciso escolher uma forma de pagamento e parcelas!');
        }

        try {

            $product = Product::find($request->product_id);
            if (!$product) {
                return redirect()->back()->with('info', 'Ops, Produto não encontrado ou disponível!');
            }
    
            $customer = $this->createUser($request->name, $request->email, $request->phone, $request->cpfcnpj);
            if (!$customer) {
                return redirect()->back()->with('info', 'Verifique os dados e tente novamente!');
            }
    
            $assas = new AssasController();
            $charge = $assas->createInvoice($request->method, $request->installments, $customer['customer'], $product->value, $product->name);
            if (!$charge) {
                return redirect()->back()->with('info', 'Ops, não foi possível finalizar a compra. Verifique seus dados e tente novamente!');
            }
    
            $sale = new Sale();
            $sale->user_id = $customer['id'];
            $sale->product_id = $product->id;
            $sale->payment_method = $request->method;
            $sale->payment_token = $charge['id'];
            $sale->payment_url = $charge['invoiceUrl'];
            $sale->quanty = $request->quanty;
    
            if ($sale->save()) {
                return redirect($charge['invoiceUrl']);
            }
    
            return redirect()->back()->with('info', 'Erro ao registrar a venda. Por favor, tente novamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('info', 'Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.');
        }
    }

    private function createUser($name, $email, $phone, $cpfcnpj) {
        try {
            $user = User::where('email', $email)->orWhere('cpfcnpj', $cpfcnpj)->first();
    
            if ($user) {
                $user->phone    = preg_replace('/\D/', '', $phone);
                $user->cpfcnpj  = preg_replace('/\D/', '', $cpfcnpj);
                $user->name     = $name;
                $user->save();
            } else {
                $user           = new User();
                $user->name     = $name;
                $user->email    = $email;
                $user->phone    = preg_replace('/\D/', '', $phone);
                $user->cpfcnpj  = preg_replace('/\D/', '', $cpfcnpj);
                $user->password = bcrypt(preg_replace('/\D/', '', $cpfcnpj));
                $user->save();
            }
    
            $assas = new AssasController();
            $customer = $user->customer ?? $assas->createCustomer($user->id);
            if (!$customer) {
                return false;
            }
    
            return [
                'id' => $user->id,
                'customer' => $customer,
            ];
        } catch (\Exception $e) {
            return false;
        }
    }    
}
