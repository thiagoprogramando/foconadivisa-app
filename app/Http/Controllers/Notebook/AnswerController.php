<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Notebook;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller {
    
    public function answer($notebook) {

        $notebook = Notebook::find($notebook);
        if($notebook) {
            $answeredQuestionIds = $notebook->answers->pluck('question_id')->toArray();
            $unansweredQuestions = $notebook->questions()->whereNotIn('questions.id', $answeredQuestionIds)->paginate(1);
            return view('app.Notebook.Quiz.question-notebook', compact('notebook', 'unansweredQuestions'));
        }
    }

    public function answerReview($answer) {

        $answer = Answer::find($answer);
        if($answer) {
            return view('app.Notebook.Quiz.question-review', [
                'answer' => $answer,
            ]);
        }
    }

    public function submitAnswerAndNext(Request $request, $notebookId, $questionId, $page) {
        
        $request->validate([
            'option_id' => 'required|exists:options,id',
        ]);
    
        $notebook = Notebook::find($notebookId);
        if(!$notebook) {
            return redirect()->back()->with('error', 'O caderno não foi encontrado.');
        }
    
        $answer                 = new Answer();
        $answer->notebook_id    = $notebook->id;
        $answer->question_id    = $questionId;
        $answer->option_id      = $request->input('option_id');
        $answer->save();
    
        return redirect()->route('answer-review', [$answer->id])->with('success', 'Resposta salva!');
    }
}
