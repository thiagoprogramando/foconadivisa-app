<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model {

    use HasFactory;

    protected $table = "options";

    protected $fillable = [
        'question_id',
        'option_number',
        'option_text',
        'is_correct',
    ];

    public function answers() {
        return $this->hasMany(Answer::class, 'option_id');
    }
}
