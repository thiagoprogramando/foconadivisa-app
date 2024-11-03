<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller {
    
    public function shop() {

        $sales = Sale::where('user_id', Auth::user()->id)->where('payment_status', 1)->orderBy('created_at', 'asc')->get();
        return view('app.Sale.shop', [
            'sales' => $sales
        ]);
    }
}
