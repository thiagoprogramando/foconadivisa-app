<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class PlanController extends Controller {
    
    public function plans() {

        $plans = Plan::orderBy('value', 'asc')->get();
        return view('app.User.plan', [
            'plans' => $plans
        ]);
    }

    public function createPlan(Request $request) {
        
        $plan               = new Plan();
        $plan->name         = $request->name;
        $plan->description  = $request->description;
        $plan->value        = $request->value;
        if($plan->save()) {
            return redirect()->route('plano', ['id' => $plan->id])->with('success', 'Plano criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Tivemos um pequeno problema, tente novamente mais tarde.');
    }

    public function updatePlan(Request $request) {
        
        $plan = Plan::find($request->id);
        if($plan) {

            $plan->name         = $request->name;
            $plan->description  = $request->description;
            $plan->value        = $request->value;
            if($plan->save()) {  
                
                return redirect()->back()->with('success', 'Plano atualizado com sucesso!');
            }
        }
        
        return redirect()->back()->with('error', 'Ops! Não foram localizados dados do Plano.');
    }

    public function deletePlan(Request $request) {

        $plan = Plan::find($request->id);
        if($plan) {

            $plan->subjects()->detach();
            if($plan->delete()) {
                return redirect()->back()->with('success', 'Plano excluído com sucesso!');
            }
        }

        return redirect()->back()->with('error', 'Ops! Tivemos um pequeno problema, tente novamente mais tarde.');
    }

    public function viewPlan($id) {

        $plan = Plan::find($id);
        if($plan) {

            $subjects            = Subject::orderBy('name', 'desc')->get();
            $topics              = Topic::orderBy('name', 'desc')->get();
            $associatedSubjects  = $plan->subjects;
            $associatedTopics    = $plan->topics;
            

            return view('app.User.view-plan', [
                'plan'               => $plan,
                'subjects'           => $subjects,
                'topics'             => $topics,
                'associatedSubjects' => $associatedSubjects,
                'associatedTopics'   => $associatedTopics 
            ]);
        }

        return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
    }

    public function addSubject(Request $request) {

        $plan = Plan::find($request->plan_id);
        if(!$plan) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
        }

        $subject = Subject::find($request->subject_id);
        if(!$subject) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do conteúdo.');
        }

        if (!$plan->subjects()->where('subject_id', $subject->id)->exists()) {
            $plan->subjects()->attach($subject);
            return redirect()->back()->with('success', 'Conteúdo associado ao Plano!');
        } else {
            return redirect()->back()->with('error', 'Conteúdo já está associado ao Plano.');
        }
    }

    public function deleteSubjectAssociate(Request $request) {

        $plan = Plan::findOrFail($request->plan_id);
        if ($plan->subjects()->where('subject_id', $request->subject_id)->exists()) {

            $plan->subjects()->detach($request->subject_id);
            return redirect()->back()->with('success', 'Conteúdo removido do Plano com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi encontrada a associação entre o Plano e o Conteúdo.');
    }

    public function addTopic(Request $request) {
        
        $plan = Plan::find($request->plan_id);
        if ($plan) {
            
            $topic = Topic::where('id', $request->topic_id)->first();
            if ($topic) {
                
                $plan->topics()->attach($topic);
                return redirect()->back()->with('success', 'Tópico associado ao Plano com sucesso!');
            }
            
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do tópico.');
        }
        
        return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
    }

    public function deleteTopicAssociate(Request $request) {

        $plan = Plan::find($request->plan_id);
        if ($plan) {

            if ($plan->topics()->where('topic_id', $request->topic_id)->exists()) {
                
                $plan->topics()->detach($request->topic_id);
                return redirect()->back()->with('success', 'Tópico desassociado do Plano com sucesso!');
            }
            
            return redirect()->back()->with('error', 'Ops! Este tópico não está associado ao plano.');
        }
        
        return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
    }
}
