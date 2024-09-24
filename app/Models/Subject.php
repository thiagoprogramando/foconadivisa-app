<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Subject extends Model {

    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['subject_id', 'name', 'description'];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_subject');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function countQuestions() {
        return $this->questions()->count();
    }

    public function topics() {
        return $this->hasMany(Subject::class, 'subject_id')->where('type', 2);
    }
    
    public function countTopics() {
        return $this->topics()->count();
    }

    public function countTopicQuestions() {
        return $this->hasManyThrough(Question::class, Subject::class, 'subject_id', 'subject_id')
                ->where('type', 2)
                ->count();
    }

    public function questionResolved() {
        
        $userId = Auth::id();
        
        return $this->questions()
            ->whereHas('answers', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
    }

    public function questionFail() {

        $userId = Auth::id();

        return $this->questions()
            ->whereHas('answers', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 2);
            })->count();
    }
}
