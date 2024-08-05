<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {

    use HasFactory;

    protected $table = 'plan';

    protected $fillable = [
        'name',
        'description',
        'value',
    ];

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'plan_subject');
    }

    public function topics() {
        return $this->belongsToMany(Topic::class, 'plan_topic');
    }
}
