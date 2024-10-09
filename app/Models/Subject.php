<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Subject extends Model {

    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['subject_id', 'type', 'name', 'description'];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_subject');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function totalQuestions() {
        $count = $this->questions()->count();
        foreach ($this->topics as $subsubject) {
            $count += $subsubject->totalQuestions();
        }

        return $count;
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
        
        $topicIds = $this->topics()->pluck('id')->toArray();
        $subjectIds = array_merge([$this->id], $topicIds);

        $questionIds = Question::whereIn('subject_id', $subjectIds)->pluck('id');
        $count = Answer::whereIn('question_id', $questionIds)
                   ->where('user_id', $userId)
                   ->distinct('question_id')
                   ->count('question_id');

        return $count;
    }

    public function questionFail() {
        
        $userId = Auth::id();
    
        $topicIds = $this->topics()->pluck('id')->toArray();
        $subjectIds = array_merge([$this->id], $topicIds);

        $questionIds = Question::whereIn('subject_id', $subjectIds)->pluck('id');

        $count = Answer::whereIn('question_id', $questionIds)
                   ->where('user_id', $userId)
                   ->where('status', 2)
                   ->distinct('question_id')
                   ->count('question_id');

        return $count;
    }    
}
