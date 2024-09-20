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
        $plan->type         = $request->type;
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
            $plan->type         = $request->type;
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

            $subjects            = Subject::where('type', 1)->orderBy('name', 'desc')->get();
            $topics              = Subject::where('type', 2)->orderBy('name', 'desc')->get();
            $associatedSubjects  = $plan->subjects;
            $associatedTopics    = $plan->topics;
            

            return view('app.User.view-plan', [
                'plan'               => $plan,
                'subjects'           => $subjects,
                'associatedSubjects' => $associatedSubjects,
                'topics'             => $topics,
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
    
        if (!is_array($request->subject_id) || empty($request->subject_id)) {
            return redirect()->back()->with('error', 'Nenhum conteúdo foi selecionado.');
        }
    
        $subjectsToAdd = [];
        foreach ($request->subject_id as $subjectId) {
            
            $subject = Subject::find($subjectId);
            if ($subject && !$plan->subjects()->where('plan_subject.subject_id', $subject->id)->exists()) {
                $subjectsToAdd[] = $subject->id;
            }
        }
    
        if (count($subjectsToAdd) > 0) {
            $plan->subjects()->attach($subjectsToAdd);
            return redirect()->back()->with('success', 'Conteúdos adicionados ao Plano com sucesso!');
        }
    
        return redirect()->back()->with('info', 'Todos os conteúdos selecionados já estão associados ao Plano.');
    }    

    public function addTopic(Request $request) {

        $plan = Plan::find($request->plan_id);
        if(!$plan) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
        }
    
        if (!is_array($request->subject_id) || empty($request->subject_id)) {
            return redirect()->back()->with('error', 'Nenhum Tópico foi selecionado.');
        }
    
        $subjectsToAdd = [];
        foreach ($request->subject_id as $subjectId) {
            
            $subject = Subject::find($subjectId);
            if ($subject && !$plan->topics()->where('plan_subject.subject_id', $subject->id)->exists()) {
                $subjectsToAdd[] = $subject->id;
            }
        }
    
        if (count($subjectsToAdd) > 0) {
            $plan->subjects()->attach($subjectsToAdd);
            return redirect()->back()->with('success', 'Tópicos adicionados ao Plano com sucesso!');
        }
    
        return redirect()->back()->with('info', 'Todos os Tópicos selecionados já estão associados ao Plano.');
    }    

    public function deleteSubjectAssociate(Request $request) {

        $plan = Plan::findOrFail($request->plan_id);
        if ($plan->subjects()->where('plan_subject.subject_id', $request->subject_id)->exists()) {

            $plan->subjects()->detach($request->subject_id);
            return redirect()->back()->with('success', 'Conteúdo removido do Plano com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi encontrada a associação entre o Plano e o Conteúdo.');
    }

    public function deleteTopicAssociate(Request $request) {

        $plan = Plan::findOrFail($request->plan_id);
        if ($plan->topics()->where('plan_subject.subject_id', $request->subject_id)->exists()) {

            $plan->topics()->detach($request->subject_id);
            return redirect()->back()->with('success', 'Tópico removido do Plano com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi encontrada a associação entre o Plano e o Conteúdo.');
    }
}
