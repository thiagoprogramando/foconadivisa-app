<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {

    use HasFactory;

    protected $table = 'plan';

    protected $fillable = [
        'type',
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

    public function typeLabel() {
        switch ($this->type) {
            case 1:
                return 'Mês';
                break;
            case 2:
                return 'Ano';
                break;
            case 3:
                return 'Vitalício';
                break;
            default:
                return '---';
        }
    }
}
