<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'user_id',
        'plan_id',
        'value',
        'payment_token',
        'payment_url',
        'payment_status',
    ];

    public function labelUser() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function labelPlan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function statusLabel() {
        switch ($this->payment_status) {
            case 0:
                return 'Pendente de Pagamento';
                break;
            case 1:
                return 'Aprovado';
                break;
            default:
                return '---';
        }
    }
}
