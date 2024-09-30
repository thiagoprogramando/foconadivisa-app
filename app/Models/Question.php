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
        return $this->belongsTo(Subject::class);
    }

    public function topic() {
        return $this->belongsTo(Subject::class, 'subject_id')->where('type', 2);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function responsesCount($userId) {
        return $this->answers()->where('user_id', $userId)->count();
    }

    public function correctCount($userId) {
        return $this->answers()
            ->where('user_id', $userId)
            ->whereHas('option', function ($query) {
                $query->where('is_correct', true);
            })
            ->count();
    }

    public function wrogCount($userId) {
        $totalResponses = $this->responsesCount($userId);
        $correctAnswers = $this->correctCount($userId);
        return $totalResponses - $correctAnswers;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($question) {
            $question->options()->delete();
        });
    }
}
