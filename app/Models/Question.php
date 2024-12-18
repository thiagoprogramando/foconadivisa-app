<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    use HasFactory;

    protected $table = "questions";

    protected $fillable = [
        'subject_id',
        'question_text',
        'comment_text',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function topic() {
        return $this->belongsTo(Subject::class, 'subject_id')->where('type', 2);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function correctOption() {
        return $this->options()->where('is_correct', 1)->first();
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function responsesCount($userId, $notebookId = null, $questionId = null) {
        $query = Answer::where('user_id', $userId);
        
        if ($notebookId) {
            $query->whereHas('notebookQuestion', function ($q) use ($notebookId) {
                $q->where('notebook_id', $notebookId);
            });
        }

        if ($questionId) {
            $query->where('question_id', $questionId);
        }
        
        return $query->count();
    } 
    
    public function responsesCountGeneral($notebookId = null, $userId = null, $questionId = null) {

        $query = Answer::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($questionId) {
            $query->where('question_id', $questionId);
        }

        if (!is_null($notebookId)) {
            $query->whereHas('notebookQuestion', function ($q) use ($notebookId) {
                $q->where('notebook_id', $notebookId);
            });
        }

        return $query->count();
    }
    
    public function correctCount($userId, $notebookId = null, $questionId = null) {
        $query = Answer::where('user_id', $userId)->where('status', 1);
        
        if (!is_null($notebookId)) {
            $query->whereHas('notebookQuestion', function ($q) use ($notebookId) {
                $q->where('notebook_id', $notebookId);
            });
        }

        if ($questionId) {
            $query->where('question_id', $questionId);
        }
        
        return $query->count();
    }    

    public function correctCountGeneral($notebookId = null, $userId = null, $questionId = null) {

        $query = Answer::where('status', 1);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($questionId) {
            $query->where('question_id', $questionId);
        }

        if (!is_null($notebookId)) {
            $query->whereHas('notebookQuestion', function ($q) use ($notebookId) {
                $q->where('notebook_id', $notebookId);
            });
        }

        return $query->count();
    }
    
    public function wrogCount($userId, $notebookId = null, $questionId = null) {
        $totalResponses = $this->responsesCount($userId, $notebookId, $questionId);
        $correctAnswers = $this->correctCount($userId, $notebookId, $questionId);
    
        return $totalResponses - $correctAnswers;
    }   
    
    public function wrongCountGeneral($notebookId = null, $userId = null, $questionId = null) {

        $totalResponses = $this->responsesCountGeneral($notebookId, $userId, $questionId);
        $correctAnswers = $this->correctCountGeneral($notebookId, $userId, $questionId);

        return $totalResponses - $correctAnswers;
    }

    public function getAnswerDistribution() {

        // Total de respostas associadas à questão
        $totalAnswers = $this->answers()->count();

        // Se não houver respostas, retorne 0% para todas as opções
        if ($totalAnswers === 0) {
            return [
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0,
                'E' => 0,
            ];
        }

        // Obtenha o total de respostas por opção
        $distribution = $this->options()
            ->withCount(['answers'])
            ->get()
            ->mapWithKeys(function ($option) use ($totalAnswers) {
                $key = chr(64 + $option->option_number); // Converte número para letra (1 => A, 2 => B, etc.)
                $percentage = ($option->answers_count / $totalAnswers) * 100;
                return [$key => round($percentage, 2)]; // Retorna com 2 casas decimais
            })
            ->toArray();

        // Preenche opções sem respostas com 0%
        return array_merge(
            ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0],
            $distribution
        );
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($question) {
            $question->options()->delete();
        });
    }
}
