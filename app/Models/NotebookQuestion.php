<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotebookQuestion extends Model {

    use HasFactory;

    protected $table = "notebook_questions";

    protected $fillable = [
        'notebook_id',
        'question_id',
    ];

    public function notebook() {
        return $this->belongsTo(Notebook::class, 'notebook_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class, 'notebook_question_id');
    }  
}
