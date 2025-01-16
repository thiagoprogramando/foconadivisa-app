<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Notebook;
use App\Models\NotebookQuestion;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller {
    
    // public function answer($notebook, $next_question = null) {

    //     $notebook = Notebook::find($notebook);
    //     if ($notebook) {

    //         $answeredNotebookQuestionIds = Answer::where('notebook_id', $notebook->id)->pluck('notebook_question_id')->toArray();

    //         $totalQuestions = NotebookQuestion::where('notebook_id', $notebook->id)
    //         ->whereHas('question', function ($query) {
    //             $query->whereNotNull('question_text')
    //                   ->where('question_text', '!=', '');
    //         })
    //         ->count();

    //         $unansweredQuestionsQuery = NotebookQuestion::where('notebook_id', $notebook->id)
    //         ->whereNotIn('id', $answeredNotebookQuestionIds)
    //         ->whereHas('question', function ($query) {
    //             $query->whereNotNull('question_text')
    //                   ->where('question_text', '!=', '');
    //         });

    //         if ($next_question) {
    //             $unansweredQuestionsQuery->where('id', '!=', $next_question);
    //         }

    //         $unansweredQuestions = $unansweredQuestionsQuery->paginate(1);
    //         $nextQuestionNumber = $totalQuestions - $unansweredQuestions->total() + 1;
    //         $menu = 1;

    //         return view('app.Notebook.Quiz.question-notebook', compact('notebook', 'unansweredQuestions', 'totalQuestions', 'nextQuestionNumber', 'menu'));
    //     }
    // }

    public function answer($notebook, $next_question = null) {
        
        $notebook = Notebook::find($notebook);
        if ($notebook) {
            $answeredNotebookQuestionIds = Answer::where('notebook_id', $notebook->id)
                ->pluck('notebook_question_id')
                ->toArray();
    
            $totalQuestions = NotebookQuestion::where('notebook_id', $notebook->id)
                ->whereHas('question', function ($query) {
                    $query->whereNotNull('question_text')
                          ->where('question_text', '!=', '');
                })
                ->count();
    
            $unansweredQuestionsQuery = NotebookQuestion::where('notebook_id', $notebook->id)
                ->whereNotIn('id', $answeredNotebookQuestionIds)
                ->whereHas('question', function ($query) {
                    $query->whereNotNull('question_text')
                          ->where('question_text', '!=', '');
                })
                ->inRandomOrder();
            $unansweredQuestions = $unansweredQuestionsQuery->paginate(1);
            $nextQuestionNumber = $totalQuestions - $unansweredQuestions->total() + 1;
            $menu = 1;
    
            return view('app.Notebook.Quiz.question-notebook', compact('notebook', 'unansweredQuestions', 'totalQuestions', 'nextQuestionNumber', 'menu'));
        }
    }    

    public function answerReview($answer) {

        $answer = Answer::find($answer);
        if($answer) {

            $notebook = $answer->notebook;

            $totalQuestions = NotebookQuestion::where('notebook_id', $notebook->id)
            ->whereHas('question', function ($query) {
                $query->whereNotNull('question_text')
                      ->where('question_text', '!=', '');
            })
            ->count();

            $answeredQuestions = Answer::where('notebook_id', $notebook->id)
            ->whereIn('notebook_question_id', function ($query) use ($notebook) {
                $query->select('id')
                      ->from('notebook_questions')
                      ->where('notebook_id', $notebook->id);
            })
            ->orderBy('created_at')
            ->pluck('notebook_question_id')
            ->toArray();
            
            $currentQuestionNumber = array_search($answer->notebook_question_id, $answeredQuestions) + 1;
            
            return view('app.Notebook.Quiz.question-review', [
                'answer' => $answer,
                'totalQuestions' => $totalQuestions,
                'currentQuestionNumber' => $currentQuestionNumber,
                'menu'   => 1
            ]);
        }
    }

    public function submitAnswerAndNext(Request $request, $notebookId, $notebookQuestionId, $page) {
        
        $request->validate([
            'option_id' => 'required|exists:options,id',
            'notebook_question_id' => 'required|exists:notebook_questions,id',
        ]);

        $notebook = Notebook::find($notebookId);
        if (!$notebook) {
            return redirect()->back()->with('error', 'O caderno não foi encontrado.');
        }

        $notebookQuestion = NotebookQuestion::find($notebookQuestionId);
        if (!$notebookQuestion) {
            return redirect()->back()->with('error', 'A questão não foi encontrada.');
        }

        $question = $notebookQuestion->question;
        if (!$question) {
            return redirect()->back()->with('error', 'A questão não foi encontrada.');
        }

        $answer = new Answer();
        $answer->user_id = Auth::id();
        $answer->notebook_id = $notebook->id;
        $answer->notebook_question_id = $notebookQuestion->id; 
        $answer->question_id = $question->id;
        $answer->option_id = $request->input('option_id');
        $answer->status = $this->calculateAnswerStatus($question, $request->input('option_id'));
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
