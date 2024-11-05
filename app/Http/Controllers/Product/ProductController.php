<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller {

    public function formCreateProduct() {
        return view('app.Product.create-product');
    }

    public function formUpdateProduct($id) {

        $product = Product::with('payments')->find($id);
        return view('app.Product.update-product', [
            'product' => $product
        ]);
    }
    
    public function products(Request $request) {

        $query = Product::orderBy('name', 'asc');

        if(!empty($request->name)) {
            $query->where('name', 'LIKE', '%'.$request->name.'%');
        }

        if(!empty($request->status)) {
            $query->where('status', $request->status);
        }

        $products = $query->get();

        return view('app.Product.list-product', [
            'products' => $products,
        ]);
    }

    public function createProduct(Request $request) {

        $request->validate([
            'photo'       => 'file|max:25600',
            'file'        => 'file|max:25600',
        ], [
            'photo.max' => 'Tamanho máximo para Capa (photo) = 250MB.',
            'file.max'  => 'Tamanho máximo para Arquivo (file) = 25MB.',
        ]);

        $product                = new Product();
        $product->name          = $request->name;
        $product->description   = $request->description;
        $product->value         = $this->formatarValor($request->value);
        $product->status        = $request->status;

        if ($request->hasFile('photo')) {
            $fileName = Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->store('products-photo', $fileName, 'public');
            $product->photo = $path;
        }

        if ($request->hasFile('file')) {
            $fileName = Str::uuid() . '.' . $request->file('file')->getClientOriginalExtension();
            $path = $request->file('file')->store('products-file', $fileName, 'public');
            $product->file = $path;
        }

        if($product->save()) {

            if ($request->credit_card === 'on') {
                Payment::create([
                    'product_id'   => $product->id,
                    'method'       => 'CREDIT_CARD',
                    'installments' => $request->installments_credit ?? 1,
                ]);
            }
        
            if ($request->pix === 'on') {
                Payment::create([
                    'product_id'   => $product->id,
                    'method'       => 'PIX',
                    'installments' => $request->installments_pix ?? 1,
                ]);
            }
        
            if ($request->boleto === 'on') {
                Payment::create([
                    'product_id'   => $product->id,
                    'method'       => 'BOLETO',
                    'installments' => $request->installments_boleto ?? 1,
                ]);
            }

            return redirect()->route('produtos')->with('success', 'Produto criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível criar o Produto.');
    }

    public function updateProduct(Request $request) {

        $product = Product::find($request->id);
        if (!$product) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do Produto.');
        }

        $product->name          = $request->input('name');
        $product->description   = $request->input('description');
        $product->value         = $request->input('value');
        $product->status        = $request->input('status');

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::delete($product->photo);
            }

            $product->photo = $request->file('photo')->store('products/photos');
        }

        if ($request->hasFile('file')) {
            if ($product->file) {
                Storage::delete($product->file);
            }

            $product->file = $request->file('file')->store('products/files');
        }

        $product->save();

        $paymentMethods = ['credit_card', 'pix', 'boleto'];
        foreach ($paymentMethods as $method) {
            
            if ($request->has($method)) {
                
                $installments = $request->input("installments_{$method}", 1);
                $product->payments()->updateOrCreate(
                    ['method' => strtoupper($method)],
                    ['installments' => $installments]
                );
            } else {
                
                $product->payments()->where('method', strtoupper($method))->delete();
            }
        }

        return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso!');
    }

    public function deleteProduct(Request $request) {

        $product = Product::find($request->id);
        if($product) {

            if ($product->photo) {
                Storage::delete('public/' . $product->photo);
            }

            if ($product->file) {
                Storage::delete('public/' . $product->file);
            }

            if ($product->delete()) {
                return redirect()->back()->with('success', 'Produto excluído com sucesso!');
            }

            return redirect()->back()->with('error', 'Ops! Não foi possível excluir o Produto.');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível excluir o Produto.');
    }

    private function formatarValor($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);

        return number_format($valorFloat, 2, '.', '');
    }
}
