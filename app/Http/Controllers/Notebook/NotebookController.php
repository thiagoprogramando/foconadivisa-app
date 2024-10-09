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

            $bestPerformanceSubjects    = $notebook->getBestPerformanceSubjects();
            $worstPerformanceSubjects   = $notebook->getWorstPerformanceSubjects();
            $bestPerformanceTopics      = $notebook->getBestPerformanceTopics();
            $worstPerformanceTopics     = $notebook->getWorstPerformanceTopics();

            $selectedSubjects = NotebookQuestion::where('notebook_id', $notebook->id)
                ->with('question')
                ->get()
                ->pluck('question.subject_id')
                ->filter(function($subjectId) {
                    return Subject::find($subjectId)->type === 1;
                })
                ->toArray();

            $selectedTopics = NotebookQuestion::where('notebook_id', $notebook->id)
                ->with('question')
                ->get()
                ->pluck('question.subject_id')
                ->filter(function($subjectId) {
                    return Subject::find($subjectId)->type === 2;
                })
                ->toArray();

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
    
    public function notebookFilter($id) {
        $notebook = Notebook::find($id);
        if (!$notebook) {
            return redirect()->back()->with('error', 'Caderno não localizado na base de dados!');
        }
    
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }
    
        $plan = $user->labelPlan;
        $subjectsFromPlan = $plan ? $plan->subjects : collect();
        $topicsFromPlan = $plan ? $plan->topics : collect();
    
        $associatedSubjects = $notebook->questions->map(function ($question) {
            return $question->subject;
        })->filter(function ($subject) {
            return $subject !== null;
        });
    
        $contents = $associatedSubjects->filter(function ($subject) {
            return $subject->type == 1;
        });

        $topics = $associatedSubjects->filter(function ($subject) {
            return $subject->type == 2;
        });
    
        $parentsOfTopics = $associatedSubjects->filter(function ($subject) {
            return $subject->type == 2;
        })->map(function ($topic) {
            return Subject::find($topic->subject_id); 
        })->filter(function ($parent) {
            return $parent && $parent->type == 1;
        });
    
        $uniqueAssociatedSubjects = $contents->merge($parentsOfTopics)->unique('id');
        $selectedSubjectIds = $uniqueAssociatedSubjects->pluck('id')->toArray();
        $selectedTopicIds = $topics->pluck('id')->toArray();
    
        return view('app.Notebook.filter-notebook', [
            'notebook'              => $notebook,
            'subjectsFromPlan'      => $subjectsFromPlan,
            'selectedSubjectIds'    => $selectedSubjectIds,
            'topicsFromPlan'        => $topicsFromPlan,
            'selectedTopicIds'      => $selectedTopicIds
        ]);
    }    
    
    public function notebooks() {

        $user = Auth::user();
        if(!$user) {
            redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }

        $plan = $user->labelPlan;
        if ($plan) {
            $subjects   = $plan->subjects;
        } else {
            $subjects   = collect();
        }

        $notebooks = Notebook::where('user_id', Auth::id())->get();
        return view('app.Notebook.list-notebook', [
            'notebooks' => $notebooks,
            'subjects'  => $subjects,
            'topics'    => collect()
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

        $filter = $request->input('filter', false);

        $query = Question::query();

        if (!is_array($subjects)) {
            $subjects = [];
        }
    
        if (!is_array($topics)) {
            $topics = [];
        }

        if (!empty($subjects) || !empty($topics)) {
            $query->where(function($q) use ($subjects, $topics) {
                if (!empty($subjects)) {
                    $q->whereIn('subject_id', $subjects);
                }
        
                if (!empty($topics)) {
                    $q->orWhereIn('subject_id', $topics);
                }
            });
        }

        if ($filter == 'remove_question_resolved') {
            
            $resolvedQuestions = Answer::where('user_id', Auth::id())->pluck('question_id')->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($filter == 'show_question_fail') {
            
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
            ->where('options.is_correct', false)->where('answers.user_id', Auth::id())
            ->pluck('answers.question_id')->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        $questionsBySubject = $query->get()->groupBy('subject_id');
        $totalQuestions = [];
        foreach ($questionsBySubject as $subjectId => $questions) {
            $totalQuestions[$subjectId] = $questions->count();
        }

        $questionsNeeded = $number;
        $selectedQuestions = collect();

        foreach ($totalQuestions as $subjectId => $count) {
            if ($questionsNeeded <= 0) break;
    
            $questionsToSelect = min(intval($number / count($totalQuestions)), $count);
            $selectedQuestions = $selectedQuestions->merge($questionsBySubject[$subjectId]->random($questionsToSelect));
            $questionsNeeded -= $questionsToSelect;
        }

        if ($questionsNeeded > 0) {
            $remainingQuestions = $query->inRandomOrder()->take($questionsNeeded)->get();
            $selectedQuestions = $selectedQuestions->merge($remainingQuestions);
        }

        $selectedQuestions = $selectedQuestions->shuffle()->take($number);
        DB::transaction(function () use ($notebook, $selectedQuestions) {
            foreach ($selectedQuestions as $question) {
                NotebookQuestion::create([
                    'notebook_id' => $notebook->id,
                    'question_id' => $question->id,
                ]);
            }
        });

        if (!$selectedQuestions->isEmpty()) {

            $notification               = new Notification();
            $notification->user_id      = Auth::user()->id;
            $notification->type         = 1;
            $notification->title        = 'Caderno de questões criado!';
            $notification->description  = 'Você já pode acessar e responder às questões responder do seu novo caderno!';
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

        $notebook->status       = 0;
        $notebook->name         = $request->name;
        $notebook->percentage   = 0;
        if (!$notebook->save()) {
            return redirect()->back()->with('error', 'Caderno de questões não foi encontrado!');
        }

        NotebookQuestion::where('notebook_id', $notebook->id)->delete();
        
        $subjects = $request->input('subject', []);
        $topics   = $request->input('topic', []);
        $number   = max(1, $request->input('number'));
    
        $filter = $request->input('filter', false);
    
        $query = Question::query();
    
        if (!is_array($subjects)) {
            $subjects = [];
        }
    
        if (!is_array($topics)) {
            $topics = [];
        }
    
        if (!empty($topics) || !empty($subjects)) {
            $query->where(function ($q) use ($subjects, $topics) {
                if (!empty($subjects)) {
                    $q->whereIn('subject_id', $subjects);
                }
    
                if (!empty($topics)) {
                    $q->orWhereIn('subject_id', $topics);
                }
            });
        }

        if ($filter == 'remove_question_resolved') {
            
            $resolvedQuestions = Answer::where('user_id', Auth::id())
            ->pluck('question_id')
            ->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($filter == 'show_question_fail') {
            
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
            ->where('options.is_correct', false)
            ->where('answers.user_id', Auth::id())
            ->pluck('answers.question_id')
            ->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        $questionsBySubject = $query->get()->groupBy('subject_id');
        $totalQuestions = [];
        foreach ($questionsBySubject as $subjectId => $questions) {
            $totalQuestions[$subjectId] = $questions->count();
        }

        $questionsNeeded = $number;
        $selectedQuestions = collect();

        foreach ($totalQuestions as $subjectId => $count) {
            if ($questionsNeeded <= 0) break;
    
            $questionsToSelect = min(intval($number / count($totalQuestions)), $count);
            $selectedQuestions = $selectedQuestions->merge($questionsBySubject[$subjectId]->random($questionsToSelect));
            $questionsNeeded -= $questionsToSelect;
        }

        if ($questionsNeeded > 0) {
            $remainingQuestions = $query->inRandomOrder()->take($questionsNeeded)->get();
            $selectedQuestions = $selectedQuestions->merge($remainingQuestions);
        }

        $selectedQuestions = $selectedQuestions->shuffle()->take($number);
        DB::transaction(function () use ($notebook, $selectedQuestions) {
            foreach ($selectedQuestions as $question) {
                NotebookQuestion::create([
                    'notebook_id' => $notebook->id,
                    'question_id' => $question->id,
                ]);
            }
        });

        if (!$selectedQuestions->isEmpty()) {

            $notification               = new Notification();
            $notification->user_id      = Auth::user()->id;
            $notification->type         = 1;
            $notification->title        = 'Caderno de atualizado!';
            $notification->description  = 'Você já pode acessar e responder às novas questões responder do seu caderno!';
            $notification->save();

            return redirect()->route('caderno', ['id' => $notebook->id])->with('success', 'Caderno atualizado com sucesso! Foram adicionados '.$selectedQuestions->count().' novas questões');
        } else {
            return redirect()->back()->with('error', 'Erro ao criar o caderno. Nenhuma questão encontrada.');
        }
    }    

    public function deleteNotebook(Request $request) {
        
        $notebook = Notebook::find($request->id);
        if($notebook) {
            if ($notebook->delete()) {
                return redirect()->back()->with('success', 'Caderno movido para a lixeira com sucesso!');
            }
    
            return redirect()->back()->with('error', 'Não foi possível mover o caderno para a lixeira, tente novamente mais tarde!');
        }
    
        return redirect()->back()->with('error', 'Não foi possível mover o caderno para a lixeira, tente novamente mais tarde!');
    }    

    public function deleteGetNotebook($id) {

        $notebook = Notebook::find($id);
        if($notebook && $notebook->delete()) {

            return redirect()->route('cadernos')->with('success', 'Caderno excluído com sucesso!');
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
