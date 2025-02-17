<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Jury;
use App\Models\NotebookQuestion;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionController extends Controller {

    public function viewQuestion($id) {

        $question   = Question::find($id);
        $subject    = Subject::find($question->subject_id);
        $topics     = Subject::where('type', 2)->where('subject_id', $subject->id)->get();
        $options    = $question->options->keyBy('option_number');

        return view('app.Subject.Question.create-question', [
            'subject'   => $subject,
            'topics'    => $topics,
            'question'  => $question,
            'options'   => $options,
            'juries'    => Jury::all()
        ]);
    }

    public function question($id) {

        $question   = Question::find($id);
        if (!$question) {
            return redirect()->route('app')->with('error', 'Ops! Não foi possível concluir essa operação.');
        }

        $answerDistribution = $question->getAnswerDistribution();

        return view('app.Subject.Question.view-question', [
            'question'              => $question,
            'answerDistribution'    => $answerDistribution
        ]);
    }

    public function createQuestion($topic) {

        $question               = new Question();
        $question->subject_id   = $topic;
        if($question->save()) {
            return redirect()->route('questao', ['id' => $question->id])->with('success', 'Preencha todos os dados da nova questão!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function updateQuestion(Request $request) {

        if (empty($request->jury_id) || empty($request->subject_id)) {
            return redirect()->back()->with('info', 'Preencha os dados corretamente!');
        }

        $question = Question::find($request->id);
        if ($question) {

            $optionCount = 0;
            $correctCount = 0;
    
            for ($i = 1; $i <= 5; $i++) {
                $optionText = $request->input("option_{$i}");
                $isCorrect = $request->input("is_correct_{$i}") ? true : false;
    
                if ($optionText) {
                    $optionCount++;
                    if ($isCorrect) {
                        $correctCount++;
                    }
                }
            }

            if ($optionCount < 2) {
                return redirect()->back()->with('error', 'Você deve fornecer pelo menos duas opções!');
            }
    
            if ($correctCount === 0) {
                return redirect()->back()->with('error', 'Você deve marcar pelo menos uma opção como correta!');
            }

            $question->question_text = $request->input('question_text');
            $question->subject_id    = $request->input('subject_id') ?? $request->input('subject_id_question');
            $question->jury_id       = $request->input('jury_id');
            $question->comment_text  = $request->input('comment_text');
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
    
                return redirect()->route('create-question', ['topic' => $request->input('subject_id_question') ?? $request->input('subject_id')])->with('success', 'Dados salvos com sucesso!');
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

    public function deleteQuestionAnswer($notebook, $question) {

        $question = NotebookQuestion::where('question_id', $question);
        if(!$question) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrados dados da Questão.');
        }
        
        if($question && $question->delete()) {
            return redirect()->route('answer', ['id' => $notebook])->with('success', 'Questão eliminada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function favoriteQuestion(Request $request) {

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não localizado na base de dados!']);
        }

        $question = Question::find($request->question_id);
        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Questão não localizada na base de dados!']);
        }

        $favorite = Favorite::where('question_id', $question->id)->first();
        if ($favorite && $favorite->delete()) {
            return response()->json(['success' => true, 'message' => 'Desmarcada!', 'icon' => 'bi bi-heart text-danger']);
        }

        $favorite               = new Favorite();
        $favorite->user_id      = $user->id;
        $favorite->question_id  = $question->id;
        if ($favorite->save()) {
            return response()->json(['success' => true, 'message' => 'Marcada como Favorita!', 'icon' => 'bi bi-heart-fill text-danger']);
        }

        return response()->json(['success' => false, 'message' => 'Erro ao favoritar questão!']);
    }
}
