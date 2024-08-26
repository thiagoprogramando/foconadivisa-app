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
        $subject    = Subject::find($question->subject_id);
        $topics     = Topic::where('subject_id', $question->subject_id)->get();
        $options = $question->options->keyBy('option_number');

        return view('app.Subject.Question.create-question', [
            'topics'    => $topics,
            'question'  => $question,
            'subject'   => $subject,
            'options'   => $options,
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
        if ($question) {

            $question->question_text    = $request->input('question_text');
            $question->topic_id         = $request->input('topic_id');
            $question->comment_text     = $request->input('comment_text');
            if ($question->save()) {
                
                for ($i = 1; $i <= 5; $i++) {

                    $optionText = $request->input("option_{$i}") ?: '---';;
                    $isCorrect = $request->input("is_correct_{$i}") ? true : false;
    
                    $option = Option::where('question_id', $question->id)
                                    ->where('option_number', $i)
                                    ->first();
                    
                    if ($option) {
                        $option->option_text = $optionText;
                        $option->is_correct = $isCorrect;
                    } else {
                        $option = new Option();
                        $option->question_id = $question->id;
                        $option->option_number = $i;
                        $option->option_text = $optionText;
                        $option->is_correct = $isCorrect;
                    }
    
                    $option->save();
                }
    
                return redirect()->back()->with('success', 'Dados salvos com sucesso!');
            }
        }
    
        return redirect()->back()->with('error', 'Ops! Não foram encontrados dados da Questão.');
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
