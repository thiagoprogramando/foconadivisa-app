<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'url'
    ];

    public function typeLabel() {
        switch ($this->type) {
            case 1:
                return '<i class="bi bi-info-circle text-primary"></i>';
                break;
            case 2:
                return '<i class="bi bi-check-circle text-success"></i>';
                break;
            case 3:
                return '<i class="bi bi-x-circle text-danger"></i>';
                break;
            default:
                return '<i class="bi bi-check-circle text-success"></i>';
        }
    }
}
