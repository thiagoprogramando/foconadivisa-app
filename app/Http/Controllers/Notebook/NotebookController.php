<?php

namespace App\Http\Controllers\Notebook;

use App\Http\Controllers\Controller;

use App\Models\Answer;
use App\Models\Favorite;
use App\Models\Notebook;
use App\Models\NotebookQuestion;
use App\Models\Question;
use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotebookController extends Controller {
    
    public function notebook($id, $tab = null) {

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

            $answers = Answer::where('notebook_id', $notebook->id)->get();
            return view('app.Notebook.view-notebook', [
                'notebook'                  => $notebook,
                'answers'                   => $answers,
                'bestPerformanceSubjects'   => $bestPerformanceSubjects,
                'worstPerformanceSubjects'  => $worstPerformanceSubjects,
                'bestPerformanceTopics'     => $bestPerformanceTopics,
                'worstPerformanceTopics'    => $worstPerformanceTopics,
                'tab'                       => $tab
            ]);
        }

        redirect()->back()->with('error', 'Não foram encontrados dados do caderno!');
    }
    
    public function createNotebookForm() {

        $user = Auth::user();
        if(!$user) {
            return redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }

        $plan = $user->labelPlan;
        $subjects = $plan 
            ? $plan->subjects()->where('type', 1)->get() 
            : collect();

        return view('app.Notebook.create-notebook', [
            'subjects' => $subjects
        ]);
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

        $plan               = $user->labelPlan;
        $subjectsFromPlan   = $plan ? $plan->subjects : collect();
        $notebookSubjects   = $notebook->subjects();
        $notebookTopics     = $notebook->topics();

        return view('app.Notebook.filter-notebook', [
            'notebook'          => $notebook,
            'notebookSubjects'  => $notebookSubjects,
            'notebookTopics'    => $notebookTopics,
            'subjectsFromPlan'  => $subjectsFromPlan,
        ]);
    }
    
    public function notebooks() {

        $user = Auth::user();
        if(!$user) {
            return redirect()->route('logout')->with('error', 'Faça login para acessar sua conta!');
        }

        $notebooks = Notebook::where('user_id', Auth::id())->get();
        return view('app.Notebook.list-notebook', [
            'notebooks' => $notebooks,
        ]);
    }

    public function createNotebook(Request $request) {

        $notebook = Notebook::create([
            'name'    => $request->input('name'),
            'user_id' => Auth::id(),
        ]);

        $topics = collect($request->input('topics', []))->flatten()->toArray();
        $number = max(1, intval($request->input('number', 1)));
        $filter = $request->input('filter', false);

        $query = Question::whereIn('subject_id', $topics);

        if ($filter === 'remove_question_resolved') {
            $resolvedQuestions = Answer::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($filter === 'show_question_fail') {
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
                ->where('options.is_correct', false)
                ->where('answers.user_id', Auth::id())
                ->pluck('answers.question_id')
                ->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        if ($filter === 'show_question_favorite') {
            $favoriteQuestions = Favorite::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();
            $query->whereIn('id', $favoriteQuestions);
        }

        $existingQuestions = NotebookQuestion::where('notebook_id', $notebook->id)
            ->pluck('question_id')
            ->toArray();
        $query->whereNotIn('id', $existingQuestions);

        $filteredQuestions = $query->get();

        $questionsByTopic = $filteredQuestions->groupBy('subject_id');
        $totalQuestions = $questionsByTopic->map(fn($questions) => $questions->count());
        $questionsNeeded = $number;
        $selectedQuestions = collect();

        foreach ($totalQuestions as $topicId => $count) {
            if ($questionsNeeded <= 0) break;

            $questionsToSelect = min(
                intval($questionsNeeded / count($totalQuestions)),
                $count
            );

            if ($questionsToSelect > 0 && isset($questionsByTopic[$topicId])) {
                $selectedQuestions = $selectedQuestions->merge(
                    $questionsByTopic[$topicId]->take($questionsToSelect)
                );
            }

            $questionsNeeded -= $questionsToSelect;
        }

        if ($questionsNeeded > 0) {
            $alreadySelectedIds = $selectedQuestions->pluck('id')->toArray();

            $remainingQuestions = $filteredQuestions->filter(fn($question) => 
                !in_array($question->id, $alreadySelectedIds)
            );

            $selectedQuestions = $selectedQuestions->merge(
                $remainingQuestions->shuffle()->take($questionsNeeded)
            );
        }

        if ($selectedQuestions->isNotEmpty()) {
            DB::transaction(function () use ($notebook, $selectedQuestions) {
                foreach ($selectedQuestions as $question) {
                    NotebookQuestion::create([
                        'notebook_id' => $notebook->id,
                        'question_id' => $question->id,
                    ]);
                }
            });

            return redirect()
                ->route('caderno', ['id' => $notebook->id])
                ->with('success', 'Caderno criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao criar o caderno. Nenhuma questão encontrada.');
    }

    public function updateNotebook(Request $request) {

        $notebook = Notebook::find($request->id);
        if (!$notebook) {
            return redirect()->back()->with('error', 'Caderno de questões não foi encontrado!');
        }

        $notebook->status     = 0;
        $notebook->name       = $request->name;
        $notebook->percentage = 0;

        if (!$notebook->save()) {
            return redirect()->back()->with('error', 'Erro ao salvar as informações do caderno!');
        }

        NotebookQuestion::where('notebook_id', $notebook->id)->delete();
        Answer::where('notebook_id', $notebook->id)->where('position', 1)->update(['position' => 2]);

        $topics = collect($request->input('topics', []))->flatten()->toArray();
        $number = max(1, intval($request->input('number', 1)));
        $filter = $request->input('filter', false);

        $query = Question::whereIn('subject_id', $topics);

        if ($filter === 'remove_question_resolved') {
            $resolvedQuestions = Answer::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();
            $query->whereNotIn('id', $resolvedQuestions);
        }

        if ($filter === 'show_question_fail') {
            $failedQuestions = Answer::join('options', 'answers.option_id', '=', 'options.id')
                ->where('options.is_correct', false)
                ->where('answers.user_id', Auth::id())
                ->pluck('answers.question_id')
                ->toArray();
            $query->whereIn('id', $failedQuestions);
        }

        if ($filter === 'show_question_favorite') {
            $favoriteQuestions = Favorite::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();
            $query->whereIn('id', $favoriteQuestions);
        }

        $filteredQuestions = $query->get();
        if ($filteredQuestions->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhuma questão encontrada com os filtros aplicados.');
        }

        $questionsByTopic = $filteredQuestions->groupBy('subject_id');
        $totalQuestions = $questionsByTopic->map(fn($questions) => $questions->count());
        $questionsNeeded = $number;
        $selectedQuestions = collect();

        foreach ($totalQuestions as $topicId => $count) {
            if ($questionsNeeded <= 0) break;

            $questionsToSelect = min(
                intval($questionsNeeded / count($totalQuestions)),
                $count
            );

            if ($questionsToSelect > 0 && isset($questionsByTopic[$topicId])) {
                $selectedQuestions = $selectedQuestions->merge(
                    $questionsByTopic[$topicId]->take($questionsToSelect)
                );
            }

            $questionsNeeded -= $questionsToSelect;
        }

        if ($questionsNeeded > 0) {
            $alreadySelectedIds = $selectedQuestions->pluck('id')->toArray();

            $remainingQuestions = $filteredQuestions->filter(fn($question) => 
                !in_array($question->id, $alreadySelectedIds)
            );

            $selectedQuestions = $selectedQuestions->merge(
                $remainingQuestions->shuffle()->take($questionsNeeded)
            );
        }

        if ($selectedQuestions->isNotEmpty()) {
            DB::transaction(function () use ($notebook, $selectedQuestions) {
                foreach ($selectedQuestions as $question) {
                    NotebookQuestion::updateOrCreate(
                        ['notebook_id' => $notebook->id, 'question_id' => $question->id],
                        []
                    );
                }
            });

            return redirect()
                ->route('caderno', ['id' => $notebook->id])
                ->with('success', 'Caderno atualizado com sucesso! Foram adicionadas ' . $selectedQuestions->count() . ' novas questões.');
        }

        return redirect()->back()->with('error', 'Erro ao atualizar o caderno. Nenhuma questão foi adicionada.');
    }

    public function addQuestion(Request $request) {

        $notebook = Notebook::find($request->notebook_id);
        if (!$notebook) {
            return redirect()->back()->with('info', 'Não foi possível encontrar dados do Caderno!');
        }

        $question = Question::find($request->question_id);
        if (!$question) {
            return redirect()->back()->with('info', 'Não foi possível encontrar dados da Questão!');
        }

        $notebookQuesiton = new NotebookQuestion();
        $notebookQuesiton->notebook_id = $request->notebook_id;
        $notebookQuesiton->question_id = $request->question_id;
        if($notebookQuesiton->save()) {
            return redirect()->back()->with('success', 'Questão adicionada com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível adicionar a questão ao Caderno!');
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
                Answer::where('notebook_id', $notebook->id)->where('position', 1)->update(['position' => 2]);
                return redirect()->route('caderno', ['id' => $notebook->id, 'tab' => 'contact-tab'])->with('success', 'Parabéns, o caderno foi completado com sucesso!');
            }
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível finalizar o caderno, tente novamente!');
    }
}
