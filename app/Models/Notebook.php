<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notebook extends Model {

    use HasFactory;

    protected $table = "notebooks";

    protected $fillable = [
        'user_id', 
        'name', 
        'percentage', 
        'status'
    ];

    public function questions() {
        return $this->belongsToMany(Question::class, 'notebook_questions', 'notebook_id', 'question_id');
    }    

    public function countQuestions() {
        return $this->questions()->count();
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'notebook_id');
    }

    public function getAnsweredQuestionsCount() {
        return $this->answers()->count();
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

    public function getPendingQuestionsCount() {
        return $this->questions()->count() - $this->getAnsweredQuestionsCount();
    }

    public function getCorrectAnswersCount() {
        return $this->answers->filter(function($answer) {
            return $answer->isCorrect();
        })->count();
    }

    public function getIncorrectAnswersCount() {
        return $this->getAnsweredQuestionsCount() - $this->getCorrectAnswersCount();
    }

    public function getPerformanceEvaluation() {
        $totalQuestions = $this->questions()->count();
        $correctAnswers = $this->getCorrectAnswersCount();
        
        if ($totalQuestions === 0) return 'Nenhuma questão respondida';

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

        foreach ($this->questions as $question) {
            $content = ($type == 'subject') ? $question->subject : $question->topic;
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

    // protected static function boot() {
    //     parent::boot();

    //     static::deleting(function ($notebook) {
    //         $notebook->questions()->detach();
    //     });
    // }
}
