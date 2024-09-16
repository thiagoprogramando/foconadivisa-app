<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model {

    use HasFactory;

    protected $table = 'topics';

    protected $fillable = ['id_subject', 'name', 'description'];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plan_topic');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function countQuestions() {
        return $this->questions()->count();
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($topic) {
            Question::where('topic_id', $topic->id)->delete();
            $topic->plans()->detach();
        });
    }
}
