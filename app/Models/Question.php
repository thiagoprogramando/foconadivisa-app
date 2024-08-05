<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    use HasFactory;

    protected $table = "questions";

    protected $fillable = [
        'subject_id',
        'topic_id',
        'question_text',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function topic() {
        return $this->belongsTo(Topic::class);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($question) {
            $question->options()->delete();
        });
    }
}
