<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuestionController extends Controller {

    public function viewQuestion($id) {

        $question   = Question::find($id);
        $options    = Option::where('question_id', $question->id)->get();
        $subject    = Subject::find($question->subject_id);
        $topics     = Topic::where('subject_id', $question->subject_id)->get();

        return view('app.Subject.Question.create-question', [
            'topics'    => $topics,
            'question'  => $question,
            'subject'   => $subject,
            'options'   => $options
        ]);
    }

    public function createQuestion($subject) {

        $question = new Question();
        $question->subject_id = $subject;
        
        if($question->save()) {
            return redirect()->route('questao', ['id' => $question->id])->with('success', 'Preencha todos os dados da questão!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function updateQuestion(Request $request) {

        $question = Question::find($request->id);
        $question->question_text = $request->question_text;
        $question->topic_id = $request->topic_id;
        if($question->save()) {
            return redirect()->back()->with('success', 'Dados salvos com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function deleteQuestion(Request $request) {

        $question = Question::find($request->id);
        if(!$question) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados da Questão.');
        }

        $subject = $question->subject_id;
        
        if($question && $question->delete()) {
            return redirect()->route('conteudo', ['id' => $subject])->with('success', 'Questão excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }
}
