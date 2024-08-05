<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model {

    use HasFactory;

    protected $table = 'subject';

    protected $fillable = ['name', 'description'];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_subject');
    }

    public function topics() {
        return $this->hasMany(Topic::class);
    }

    public function questions() {
        return $this->hasManyThrough(Question::class, Topic::class);
    }

    public function countTopics() {
        return $this->topics()->count();
    }

    public function countQuestions() {
        return $this->questions()->count();
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($subject) {
           
            foreach ($subject->topics as $topic) {
                $topic->questions()->delete();
            }

            $subject->topics()->delete();
            $subject->plans()->detach();
        });
    }
}
