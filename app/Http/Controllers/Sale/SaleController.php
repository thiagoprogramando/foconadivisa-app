<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Mail\Product;
use App\Models\Product as ModelsProduct;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller {

    public function sales(Request $request) {

        $query = Sale::orderBy('created_at', 'desc');

        if(!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if(!empty($request->product_id)) {
            $query->where('product_id', $request->product_id);
        }

        if(!empty($request->type)) {
            $query->where('type', $request->type);
        }

        if(!empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        if(!empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }

        $sales = $query->get();

        return view('app.Sale.list-sale', [
            'sales'    => $sales,
            'users'    => User::all(),
            'products' => ModelsProduct::all()
        ]);
    }
    
    public function confirmSale($id) {

        $sale = Sale::find($id);
        if($sale) {
            
            $send = Mail::to($sale->user->email, $sale->user->name)->send(new Product([
                'toName'    => $sale->user->name,
                'toEmail'   => $sale->user->email,
                'fromName'  => env('MAIL_FROM_NAME'),
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'subject'   => 'Seu pedido chegou✅',
                'message'   => "<b>Você comprou e chegou rapidinho!</b> Segue abaixo o produto: {$sale->product->name}. Obrigado pela compra! Para acessar o seu conteúdo/material, siga os seguintes passos: <br>
                                1 - <a href='" . env('APP_URL') . "'>Acesse nosso site</a> <br>
                                2 - Informe seu Email e senha (sua senha sempre será o CPF ou CNPJ informado na hora da compra) <br>
                                3 - Aproveite o Produto <br>"
            ]));

            if($send) {
                $sale->delivery = 1;
                $sale->payment_status = 1;
                $sale->save();

                return redirect()->back()->with('success', 'Venda confirmada e produto enviado!');
            }

            return redirect()->back()->with('error', 'Não foi possível enviar o Produto!');
        }

        return redirect()->back()->with('error', 'Não foi possível encontrar dados da venda!');
    }

    public function deleteSale(Request $request) {

        $sale = Sale::find($request->id);
        if($sale && $sale->delete()) {
            return redirect()->back()->with('success', 'Produto excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível excluir o Produto.');
    }
}
