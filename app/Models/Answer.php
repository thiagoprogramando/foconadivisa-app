<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {

    use HasFactory;

    protected $table = "answers";

    protected $fillable = [
        'user_id',
        'notebook_id',
        'notebook_question_id',
        'question_id',
        'option_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function notebook() {
        return $this->belongsTo(Notebook::class, 'notebook_id');
    }

    public function notebookQuestion() {
        return $this->belongsTo(NotebookQuestion::class, 'notebook_question_id');
    }    

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function option() {
        return $this->belongsTo(Option::class);
    }

    public function isCorrect() {
        return $this->option && $this->option->is_correct;
    }
}
