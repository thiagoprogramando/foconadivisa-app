<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;

use App\Models\Answer;
use App\Models\Notebook;
use App\Models\NotebookQuestion;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotebookController extends Controller {
    
    public function notebook($id) {

        $user = Auth::user();
        if(!$user) {
            redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }

        $notebook = Notebook::find($id);
        if($notebook) {

            $bestPerformanceSubjects = $notebook->getBestPerformanceSubjects();
            $worstPerformanceSubjects = $notebook->getWorstPerformanceSubjects();
            $bestPerformanceTopics = $notebook->getBestPerformanceTopics();
            $worstPerformanceTopics = $notebook->getWorstPerformanceTopics();

            $selectedSubjects = NotebookQuestion::where('notebook_id', $notebook->id)->with('question')->get()->pluck('question.subject_id')->filter()->toArray();
            $selectedTopics = $selectedTopics = NotebookQuestion::where('notebook_id', $notebook->id)->with('question')->get()->pluck('question.topic_id')->filter()->toArray();

            $plan = $user->labelPlan;
            if ($plan) {

                $subjects   = $plan->subjects;
                $topics     = $plan->topics;
            } else {

                $subjects   = collect();
                $topics     = collect();
            }

            $answers = Answer::where('notebook_id', $notebook->id)->get();
            return view('app.Notebook.view-notebook', [
                'notebook'                  => $notebook,
                'answers'                   => $answers,
                'bestPerformanceSubjects'   => $bestPerformanceSubjects,
                'worstPerformanceSubjects'  => $worstPerformanceSubjects,
                'bestPerformanceTopics'     => $bestPerformanceTopics,
                'worstPerformanceTopics'    => $worstPerformanceTopics,
                'overallProgress'           => $this->getOverallProgress(),
                'selectedSubjects'          => $selectedSubjects,
                'selectedTopics'            => $selectedTopics,
                'subjects'                  => $subjects,
                'topics'                    => $topics,
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
        $number     = max(1, $request->input('number'));

        $removeQuestionResolved = $request->input('remove_question_resolved', false);
        $showQuestionFail = $request->input('show_question_fail', false);

        $query = Question::query();

        if (!is_array($subjects)) {
            $subjects = [];
        }
    
        if (!is_array($topics)) {
            $topics = [];
        }

        if (!empty($subjects)) {
            $query->whereIn('subject_id', $subjects);
        }

        if (!empty($topics)) {
            $query->whereIn('topic_id', $topics);
        }

        if ($removeQuestionResolved) {
            $resolvedQuestions = Answer::whereHas('notebook', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->pluck('question_id')
            ->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($showQuestionFail) {
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
                                    ->where('options.is_correct', false)
                                    ->whereHas('notebook', function($q) {
                                        $q->where('user_id', Auth::id());
                                    })
                                    ->pluck('answers.question_id')
                                    ->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        $questions = $query->inRandomOrder()->take($number)->get();
        DB::transaction(function () use ($notebook, $questions) {
            foreach ($questions as $question) {
                NotebookQuestion::create([
                    'notebook_id' => $notebook->id,
                    'question_id' => $question->id,
                ]);
            }
        });

        if (!$questions->isEmpty()) {

            $notification               = new Notification();
            $notification->user_id      = Auth::user()->id;
            $notification->type         = 1;
            $notification->title        = 'Caderno de questões criado!';
            $notification->description  = 'Você já pode acessar e às questões responder do seu novo caderno!';
            $notification->save();

            return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Caderno criado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao criar o caderno. Nenhuma questão encontrada.');
        }
    }

    public function updateNotebook(Request $request) {
        
        $notebook = Notebook::find($request->id);
        if (!$notebook) {
            return redirect()->back()->with('error', 'Caderno de questões não foi encontrado!');
        }

        $subjects = $request->input('subject', []);
        $topics = $request->input('topics', []);
        $number = max(1, $request->input('number'));

        $removeQuestionResolved = $request->input('remove_question_resolved', false);
        $showQuestionFail = $request->input('show_question_fail', false);

        $query = Question::query();

        if (!is_array($subjects)) {
            $subjects = [];
        }

        if (!is_array($topics)) {
            $topics = [];
        }

        if (!empty($topics) && !empty($subjects)) {
            $query->where(function ($q) use ($subjects, $topics) {
                if (!empty($subjects)) {
                    $q->whereIn('subject_id', $subjects);
                }
        
                if (!empty($topics)) {
                    $q->orWhereIn('topic_id', $topics);
                }
            });
        }

        if ($removeQuestionResolved) {
            $resolvedQuestions = Answer::whereHas('notebook', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->pluck('question_id')
            ->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($showQuestionFail) {
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
                                    ->where('options.is_correct', false)
                                    ->whereHas('notebook', function($q) {
                                        $q->where('user_id', Auth::id());
                                    })
                                    ->pluck('answers.question_id')
                                    ->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        $existingQuestionIds = $notebook->questions->pluck('id')->toArray();
        if($number > $notebook->questions->count()) {

            $newsQuestions = ($number - $notebook->questions->count());
            $questions = $query->inRandomOrder()->take($newsQuestions)->get();

            $filteredQuestions = $questions->filter(function($question) use ($existingQuestionIds) {
                return !in_array($question->id, $existingQuestionIds);
            });

            DB::transaction(function () use ($notebook, $filteredQuestions) {
                foreach ($filteredQuestions as $question) {
                    NotebookQuestion::create([
                        'notebook_id' => $notebook->id,
                        'question_id' => $question->id,
                    ]);
                }
            });

            if (!$filteredQuestions->isEmpty()) {
                $notebook->update([
                    'name'      => $request->input('name'),
                    'status'    => 0,
                    'percetage' => 50,
                ]);
                return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Caderno atualizado com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Erro ao atualizar o caderno. Nenhuma questão disponível.');
            }
        }

        return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Caderno atualizado com sucesso!');
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
