<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Notebook;
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

    public function submitAnswerAndNext(Request $request, $notebookId, $questionId, $page) {
        $request->validate([
            'option_id' => 'required|exists:options,id',
        ]);
    
        $notebook = Notebook::findOrFail($notebookId);
    
        $answer                 = new Answer();
        $answer->notebook_id    = $notebook->id;
        $answer->question_id    = $questionId;
        $answer->option_id      = $request->input('option_id');
        $answer->save();
    
        return redirect()->route('answer', [$notebook->id, $page + 1])->with('success', 'Resposta salva!');
    }
}
