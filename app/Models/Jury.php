<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jury extends Model {

    use HasFactory;

    protected $table = 'juries';

    protected $fillable = [
        'name',
    ];

    public function questions() {
        return $this->hasMany(Question::class, 'jury_id');
    }
}
