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
}
