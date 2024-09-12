<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller {
    
    public function faq(Request $request) {
        
        $plans = Plan::orderBy('name', 'asc')->get();

        if(Auth::user()->type == 0) {

            $query = Faq::where(function ($query) {
                $query->where('plan_id', Auth::user()->plan_id)
                    ->orWhereNull('plan_id');
            })->orderBy('title', 'asc');

            if (!empty($request->search)) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('response', 'LIKE', '%' . $request->search . '%');
                });
            }

            $faqs = $query->get();
        } else {

            $query = Faq::orderBy('title', 'asc');

            if (!empty($request->search)) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('response', 'LIKE', '%' . $request->search . '%');
                });
            }

            $faqs = $query->get();
        }
        

        return view('app.Faq.faq', [
            'plans' => $plans,
            'faqs'  => $faqs
        ]);
    }

    public function createFaq(Request $request) {

        $faq            = new Faq();
        $faq->plan_id   = $request->plan_id;
        $faq->title     = $request->title;
        $faq->response  = $request->response;
        $faq->type      = $request->type;
        if($faq->save()) {
            return redirect()->back()->with('success', 'FAQ cadastrada com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível cadastrar FAQ!');
    }

    public function deleteFaq($id) {

        $faq = Faq::find($id);
        if($faq && $faq->delete()) {
            return redirect()->back()->with('success', 'FAQ excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir FAQ!');
    }
}
