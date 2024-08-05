<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Notebook;
use App\Models\NotebookQuestion;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotebookController extends Controller {
    
    public function notebook($id) {

        $notebook = Notebook::find($id);
        if($notebook) {

            $bestPerformanceSubjects = $notebook->getBestPerformanceSubjects();
            $worstPerformanceSubjects = $notebook->getWorstPerformanceSubjects();
            $bestPerformanceTopics = $notebook->getBestPerformanceTopics();
            $worstPerformanceTopics = $notebook->getWorstPerformanceTopics();


            $answers = Answer::where('notebook_id', $notebook->id)->get();
            return view('app.Notebook.view-notebook', [
                'notebook'                  => $notebook,
                'answers'                   => $answers,
                'bestPerformanceSubjects'   => $bestPerformanceSubjects,
                'worstPerformanceSubjects'  => $worstPerformanceSubjects,
                'bestPerformanceTopics'     => $bestPerformanceTopics,
                'worstPerformanceTopics'    => $worstPerformanceTopics,
                'overallProgress'           => $this->getOverallProgress(),
            ]);
        }

        redirect()->back()->with('error', 'Não foram encontrados dados do caderno!');
    }

    private function getOverallProgress() {
        $totalAnswers = Answer::count();
        $correctAnswers = Answer::whereHas('option', function($query) {
            $query->where('is_correct', true);
        })->count();
    
        $incorrectAnswers = $totalAnswers - $correctAnswers;
    
        return [
            'correct' => $correctAnswers,
            'incorrect' => $incorrectAnswers
        ];
    }    
    
    public function notebooks() {

        $user = Auth::user();
        if(!$user) {
            redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }

        $plan = $user->labelPlan;
        if ($plan) {

            $subjects   = $plan->subjects;
            $topics     = $plan->topics;
        } else {

            $subjects   = collect();
            $topics     = collect();
        }

        $notebooks = Notebook::where('user_id', Auth::id())->get();
        return view('app.Notebook.list-notebook', [
            'notebooks' => $notebooks,
            'subjects'  => $subjects,
            'topics'    => $topics
        ]);
    }

    public function createNotebook(Request $request) {

        $notebook = Notebook::create([
            'name'      => $request->input('name'),
            'user_id'   => Auth::id(),
        ]);

        $subjects   = $request->input('subject', []);
        $topics     = $request->input('topics', []);
        $number     = max(5, $request->input('number'));

        if (!is_array($subjects)) {
            $subjects = [];
        }
    
        if (!is_array($topics)) {
            $topics = [];
        }

        $query = Question::query();

        if (!empty($subjects)) {
            $query->whereIn('subject_id', $subjects);
        }

        if (!empty($topics)) {
            $query->whereIn('topic_id', $topics);
        }

        $questions = $query->inRandomOrder()->take($number)->get();
        foreach ($questions as $question) {
            NotebookQuestion::create([
                'notebook_id' => $notebook->id,
                'question_id' => $question->id,
            ]);
        }

        return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Caderno criado com sucesso!');
    }

    public function deleteNotebook(Request $request) {

        $notebook = Notebook::find($request->id);
        if($notebook && $notebook->delete()) {

            return redirect()->back()->with('success', 'Caderno excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o caderno, tente novamente mais tarde!');
    }

    public function completingNotebook($notebook) {
        
        $notebook = Notebook::find($notebook);
        if($notebook) {

            $notebook->percentage = 100;
            $notebook->status = 1;
            if($notebook->save()) {
                return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Parabéns, o caderno foi completado com sucesso!');
            }
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível finalizar o caderno, tente novamente!');
    }
}
