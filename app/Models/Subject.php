<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Subject extends Model {

    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['name', 'description'];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_subject');
    }

    public function topics() {
        return $this->hasMany(Topic::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function countTopics() {
        return $this->topics()->count();
    }

    public function countQuestions() {
        return $this->questions()->count();
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

    protected static function boot() {
        parent::boot();

        static::deleting(function ($subject) {
           
            $topicIds = $subject->topics()->pluck('id');
            Question::whereIn('topic_id', $topicIds)->delete();

            Topic::where('subject_id', $subject->id)->delete();
            $subject->plans()->detach();
        });
    }
}
