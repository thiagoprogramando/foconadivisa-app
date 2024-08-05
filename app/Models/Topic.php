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

    protected static function boot() {
        parent::boot();

        static::deleting(function ($topic) {
            $topic->questions()->delete();
        });
    }
}
