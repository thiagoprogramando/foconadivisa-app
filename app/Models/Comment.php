<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    use HasFactory;

    protected $table = "comments";

    protected $fillable = [
        'user_id',
        'question_id',
        'comment_id',
        'comment'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function childComments() {
        return $this->hasMany(Comment::class, 'comment_id');
    }
}
