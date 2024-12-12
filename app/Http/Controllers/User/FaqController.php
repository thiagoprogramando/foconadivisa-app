<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Ticket;
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

    public function tickets(Request $request) {

        $query = Ticket::orderBy('response_comment', 'asc');

        if (Auth::user()->type <> 1) {
            $query->where('user_id', Auth::user()->id);
        }

        if(!empty($request->search)) {
            $query->where('comment', 'LIKE', '%'.$request->search.'%');
        }

        if(!empty($request->search)) {
            $query->where('response_comment', 'LIKE', '%'.$request->search.'%');
        }
    
        return view('app.Faq.ticket', [
            'tickets' => $query->get()
        ]);
    }

    public function updateTicket(Request $request) {

        $ticket = Ticket::find($request->id);
        if(!$ticket) {
            return redirect()->back()->with('error', 'Ticket não encontrado!');
        }

        $ticket->response_comment = $request->response_comment;
        if($ticket->save()) {

            $notification               = new Notification();
            $notification->user_id      = $ticket->user_id;
            $notification->type         = 1;
            $notification->title        = 'Ticket respondido!';
            $notification->description  = 'Olá, o suporte acabou de responde sua dúvida/relato!';
            $notification->url          = env('APP_URL').'tickets';
            $notification->save();

            return redirect()->back()->with('success', 'Ticket atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível atualizar o Ticket!');
    }

    public function createTicket(Request $request) {

        $ticket                 = new Ticket();
        $ticket->user_id        = Auth::user()->id;
        $ticket->faq_id         = $request->faq_id;
        $ticket->question_id    = $request->question_id;
        $ticket->comment        = $request->comment;
        if($ticket->save()) {

            $notification               = new Notification();
            $notification->user_id      = 1;
            $notification->type         = 1;
            $notification->title        = 'Novo Ticket aberto!';
            $notification->description  = 'Olá, foi aberto um Ticket de suporte!';
            $notification->url          = env('APP_URL').'tickets';
            $notification->save();

            return redirect()->back()->with('success', 'Ticket aberto com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível enviar o Ticket!');
    }

    public function deleteTicket($id) {

        $ticket = Ticket::find($id);
        if($ticket && $ticket->delete()) {
            return redirect()->back()->with('success', 'Ticket excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir Ticket!');
    }
}
