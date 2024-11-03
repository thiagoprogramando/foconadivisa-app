<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

    use HasFactory;

    protected $table = 'faq';

    protected $fillable = [
        'plan_id',
        'title',
        'response',
        'type',
        'views'
    ];

    public function typeLabel() {
        switch ($this->type) {
            case 1:
                return 'Padrão';
                break;
            case 2:
                return 'Financeiro';
                break;
            case 3:
                return 'Produto';
                break;
            case 4:
                return 'Questões';
                break;
            case 5:
                return 'Respostas';
                break;
            case 6:
                return 'Cadernos';
                break;
            default:
                return '---';
        }
    }
}
