<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulatedQuestionAnswer extends Model {
    use HasFactory;

    protected $table = 'simulated_question_answer';

    protected $fillable = [
        'user_id',
        'simulated_id',
        'question_id',
        'option_id',
        'is_correct'
    ];
}
