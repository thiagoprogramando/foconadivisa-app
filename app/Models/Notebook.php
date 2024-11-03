<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notebook extends Model {

    use HasFactory, SoftDeletes;

    protected $table = "notebooks";

    protected $fillable = [
        'user_id', 
        'name', 
        'percentage', 
        'status'
    ];

    public function notebookQuestions() {
        return $this->hasManyThrough(Question::class, NotebookQuestion::class, 'notebook_id', 'id', 'id', 'question_id');
    }

    public function questions() {
        return $this->belongsToMany(Question::class, 'notebook_questions', 'notebook_id', 'question_id');
    }    

    public function countQuestions() {
        return $this->questions()->count();
    }

    public function countQuestionsNotebook() {
        return $this->notebookQuestions()
                    ->whereHas('answers', function($query) {
                        $query->whereColumn('notebook_question_id', 'notebook_questions.id');
                    })
                    ->count();
    }       

    public function answers() {
        return $this->hasMany(Answer::class, 'notebook_id');
    }

    public function getAnsweredQuestionsCount() {
        return $this->answers()->whereIn('notebook_question_id', function ($query) {
            $query->select('id')
                  ->from('notebook_questions')
                  ->where('notebook_id', $this->id);
        })
        ->count();
    }

    public function getPendingQuestionsCount() {
        $totalQuestions = $this->questions()
        ->whereIn('notebook_questions.id', function ($query) {
            $query->select('id')
                  ->from('notebook_questions')
                  ->where('notebook_id', $this->id);
        })
        ->count();

        return $totalQuestions - $this->getAnsweredQuestionsCount();
    }

    public function getSubjectsNames() {
        return $this->questions->load('subject')
                ->filter(function($question) {
                    return $question->subject->type === 1;
                })->pluck('subject.name')->unique()->toArray();
    }

    public function getTopicsNames() {
        return $this->questions->load('subject')
                ->filter(function($question) {
                    return $question->subject->type === 2;
                })->pluck('subject.name')->unique()->toArray();
    }

    public function getCorrectAnswersCount() {
        return $this->answers()->whereIn('notebook_question_id', function ($query) {
            $query->select('id')
                  ->from('notebook_questions')
                  ->where('notebook_id', $this->id);
        })
        ->whereHas('question', function ($query) {
            $query->where('status', 1);
        })
        ->count();
    }        

    public function getIncorrectAnswersCount() {
        return $this->getAnsweredQuestionsCount() - $this->getCorrectAnswersCount();
    }

    public function getPerformanceEvaluation() {
        $totalQuestions = $this->questions()
            ->whereIn('notebook_questions.id', function ($query) {
                $query->select('id')
                      ->from('notebook_questions')
                      ->where('notebook_id', $this->id);
            })
            ->count();
        
        $correctAnswers = $this->getCorrectAnswersCount();
        $incorrectAnswers = $this->getIncorrectAnswersCount();
        
        if ($correctAnswers == 0 && $incorrectAnswers == 0) {
            return '<span class="badge bg-dark">Neutro</span>';
        }
        
        $percentage = ($correctAnswers / $totalQuestions) * 100;
        if ($percentage < 30) {
            return '<span class="badge bg-danger">Ruim</span>';
        } elseif ($percentage < 60) {
            return '<span class="badge bg-primary">Bom</span>';
        } else {
            return '<span class="badge bg-success">Muito bom</span>';
        }
    }        

    public function getBestPerformanceSubjects() {
        return $this->calculatePerformanceByContent('subject', true);
    }
    
    public function getWorstPerformanceSubjects() {
        return $this->calculatePerformanceByContent('subject', false);
    }
    
    public function getBestPerformanceTopics() {
        return $this->calculatePerformanceByContent('topic', true);
    }
    
    public function getWorstPerformanceTopics() {
        return $this->calculatePerformanceByContent('topic', false);
    }
    
    private function calculatePerformanceByContent($type, $best = true) {

        $contents = [];
        $notebookQuestionIds = NotebookQuestion::where('notebook_id', $this->id)->pluck('question_id');

        $questions = Question::select('questions.*')
        ->join('notebook_questions as nq', 'questions.id', '=', 'nq.question_id')
        ->where('nq.notebook_id', $this->id)
        ->get();
    
        foreach ($questions as $question) {
            $content = ($type == 'subject') 
            ? ($question->subject_id && $question->subject->type === 1 ? $question->subject : null)
            : ($question->subject_id && $question->subject->type === 2 ? $question->subject : null);

            if ($content) {
                if (!isset($contents[$content->name])) {
                    $contents[$content->name] = [
                        'total' => 0,
                        'correct' => 0
                    ];
                }
                $contents[$content->name]['total']++;
                if ($question->answers()->where('notebook_id', $this->id)->first()?->isCorrect()) {
                    $contents[$content->name]['correct']++;
                }
            }
        }
    
        $results = [];
        foreach ($contents as $name => $data) {
            $percentage = ($data['correct'] / $data['total']) * 100;
            if (($best && $percentage > 50) || (!$best && $percentage <= 50)) {
                $results[] = "<span class='badge bg-dark'>{$name}</span>";
            }
        }
    
        return implode(' ', $results);
    }    
}
