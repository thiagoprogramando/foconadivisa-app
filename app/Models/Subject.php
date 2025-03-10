<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Subject extends Model {

    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type', 
        'name', 
        'description'
    ];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_subject');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'subject_id');
    }

    public function parent() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function questionsByJury($id) {
        return Question::where('subject_id', $id)
            ->select('jury_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jury_id')
            ->with('jury')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->jury->id => $item->total];
            });
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

    public function totalQuestionsFavoriteForTopic($topicId = null) {
        
        $userId = Auth::id();
        if ($topicId) {
            return Question::where('subject_id', $topicId)
                ->whereHas('favorites', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();
        }
    
        return $this->totalQuestions();
    }
    
    
    public function topicsForSubject($subjectId) {
        return $this->topics()->where('subject_id', $subjectId)->get();
    }
    
    public function questionResolved($id) {
        
        $userId = Auth::id();
        
        $questionIds = Question::where('subject_id', $id)->pluck('id');
        $count = Answer::whereIn('question_id', $questionIds)->where('user_id', $userId)->distinct('question_id')->count('question_id');

        return $count;
    }

    public function questionResolvedParent($id) {
        
        $userId = Auth::id();

        $subject = Subject::find($id);
        $questionIds = Question::whereIn('subject_id', $subject->getAllSubjectIds())->pluck('id');
        $count = Answer::whereIn('question_id', $questionIds)
                   ->where('user_id', $userId)
                   ->distinct('question_id')
                   ->count('question_id');

        return $count;
    }

    public function getAllSubjectIds() {
      
        $ids = [$this->id];
        foreach ($this->topics as $topic) {
            $ids[] = $topic->id;
        }
    
        return $ids;
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
