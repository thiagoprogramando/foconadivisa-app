<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulatedQuestion extends Model {
    use HasFactory;

    protected $table = 'simulated_questions';

    protected $fillable = [
        'simulated_id',
        'subject_id',
        'jury_id',
        'question_text',
        'comment_text'
    ];
}
