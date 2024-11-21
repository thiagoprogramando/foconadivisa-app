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

    public function parent() {
        return $this->belongsTo(Subject::class, 'subject_id');
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

    public function topicsWithComputedData() {
        return $this->topics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'subject_id' => $topic->subject_id,
                'type' => $topic->type,
                'name' => $topic->name,
                'description' => $topic->description,
                'totalQuestions' => $topic->totalQuestions(),
                'questionResolved' => $topic->questionResolved(),
                'questionFail' => $topic->questionFail(),
            ];
        });
    }    
    
    public function countTopics() {
        return $this->topics()->count();
    }

    public function countTopicQuestions() {
        return $this->hasManyThrough(Question::class, Subject::class, 'subject_id', 'subject_id')
                ->where('type', 2)
                ->count();
    }

    public function totalQuestionsForTopic($topicId = null) {
        
        if ($topicId) {
            return $this->questions()->where('subject_id', $topicId)->count();
        }
        
        return $this->totalQuestions();
    }
    
    public function topicsForSubject($subjectId) {
        return $this->topics()->where('subject_id', $subjectId)->get();
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
