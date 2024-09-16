<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Notebook;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller {
    
    public function answer($notebook) {

        $notebook = Notebook::find($notebook);
        if($notebook) {
            $answeredQuestionIds = $notebook->answers->pluck('question_id')->toArray();
            $unansweredQuestions = $notebook->questions()->whereNotIn('questions.id', $answeredQuestionIds)->paginate(1);
            $menu                = 1;

            return view('app.Notebook.Quiz.question-notebook', compact('notebook', 'unansweredQuestions', 'menu'));
        }
    }

    public function answerReview($answer) {

        $answer = Answer::find($answer);
        if($answer) {
            return view('app.Notebook.Quiz.question-review', [
                'answer' => $answer,
                'menu'   => 1
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

        $question = Question::find($questionId);
        if (!$question) {
            return redirect()->back()->with('error', 'A questão não foi encontrada.');
        }
    
        $answer                 = new Answer();
        $answer->user_id        = Auth::id();
        $answer->notebook_id    = $notebook->id;
        $answer->question_id    = $questionId;
        $answer->option_id      = $request->input('option_id');
        $answer->status         = $this->calculateAnswerStatus($question, $request->input('option_id'));
        $answer->save();
    
        return redirect()->route('answer-review', [$answer->id]);
    }

    private function calculateAnswerStatus(Question $question, $optionId) {
        
        $correctOption = $question->options()->where('is_correct', true)->first();
        if ($correctOption && $correctOption->id == $optionId) {
            return 1;
        } else {
            return 2;
        }
    }
}
