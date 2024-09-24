<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    
    public function subjects(Request $request) {

        $query = Subject::orderBy('name', 'desc')->where('type', 1);

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        $subjects = $query->paginate(30);

        return view('app.Subject.list-subject', [
            'subjects' => $subjects
        ]);
    }

    public function viewSubject($id) {

        $subject = Subject::find($id);
        if($subject) {

            $topics     = Subject::where('subject_id', $subject->id)->where('type', 2)->get();
            $questions  = Question::where('subject_id', $subject->id)->orderBy('subject_id', 'asc')->get();
            return view('app.Subject.view-subject', [
                'subject'   => $subject,
                'topics'    => $topics,
                'questions' => $questions
            ]);
        }

        return redirect()->back()->with('error', 'Ops! Não foram encontrados dados do plano.');
    }

    public function createSubject(Request $request) {

        $subject                = new Subject();
        $subject->name          = $request->name;
        $subject->description   = $request->description;
        if($subject->save()) {
            return redirect()->route('conteudo', ['id' => $subject->id])->with('success', 'Conteúdo criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function updateSubject(Request $request) {
        
        $subject = Subject::find($request->id);
        if($subject) {

            $subject->name         = $request->name;
            $subject->description  = $request->description;
            if($subject->save()) {  
                
                return redirect()->back()->with('success', 'Plano atualizado com sucesso!');
            }
        }
        
        return redirect()->back()->with('error', 'Ops! Não foram localizados dados do Plano.');
    }

    public function deleteSubject(Request $request) {

        $subject = Subject::find($request->id);
        if($subject && $subject->delete()) {

            return redirect()->back()->with('success', 'Conteúdo excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function topics(Request $request) {

        $query = Subject::orderBy('name', 'desc')->where('type', 2);

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        $topics = $query->get();

        return view('app.Subject.list-topic', [
            'topics' => $topics
        ]);
    }

    public function createTopic(Request $request) {

        $topic              = new Subject();
        $topic->name        = $request->name;
        $topic->description = $request->description;
        $topic->subject_id  = $request->subject_id;
        $topic->type        = 2;
        if($topic->save()) {

            return redirect()->back()->with('success', 'Tópico criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function deleteTopic(Request $request) {

        $topic = Subject::find($request->id);
        if($topic && $topic->delete()) {

            return redirect()->back()->with('success', 'Tópico excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

}
