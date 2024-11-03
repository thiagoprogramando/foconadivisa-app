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

        $product = Product::find($request->product_id);
        if(!$product) {
            return redirect()->back()->with('info', 'Ops, Produto não encontrado ou disponível!');
        }

        $customer = $this->createUser($request->name, $request->email, $request->phone, $request->cpfcnpj);
        if($customer == false) {
            return redirect()->back()->with('info', 'Ops, Verifique seus dados e tente novamente!');
        }

        $assas = new AssasController();
        $charge = $assas->createInvoice($request->method, $request->installments, $customer['customer'], $product->value, $product->name);
        if($charge == false) {
            return redirect()->back()->with('info', 'Ops, Verifique seus dados e tente novamente!');
        }

        $sale = new Sale();
        $sale->user_id          = $customer['id'];
        $sale->product_id       = $product->id;
        $sale->payment_method   = $request->method;
        $sale->payment_token    = $charge['id'];
        $sale->payment_url      = $charge['invoiceUrl'];
        $sale->quanty           = $request->quanty;
        if($sale->save()) {
            return redirect($charge['invoiceUrl']);
        }

        return redirect()->back()->with('info', 'Ops, Verifique seus dados e tente novamente!');
    }

    private function createUser($name, $email, $phone, $cpfcnpj) {

        $user = User::where('email', $email)->orWhere('cpfcnpj', $cpfcnpj)->first();
        if($user) {

            $user->phone    = preg_replace('/\D/', '', $phone);
            $user->cpfcnpj  = preg_replace('/\D/', '', $cpfcnpj);
            $user->name     = $name;
            $user->save();
        } else {

            $user               = new User();
            $user->name         = $name;
            $user->email        = $email;
            $user->phone        = preg_replace('/\D/', '', $phone);
            $user->cpfcnpj      = preg_replace('/\D/', '', $cpfcnpj);
            $user->password     = bcrypt(preg_replace('/\D/', '', $cpfcnpj));
            $user->save();
        }

        $assas = new AssasController();
        return $data = [
            'id'        => $user->id,
            'customer'  => $user->customer ?? $assas->createCustomer($user->id),
        ];
    }
}
