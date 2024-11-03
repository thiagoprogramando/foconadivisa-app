<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'question_id',
        'faq_id',
        'comment',
        'response_comment',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function faq() {
        return $this->belongsTo(Faq::class, 'faq_id');
    }
}
