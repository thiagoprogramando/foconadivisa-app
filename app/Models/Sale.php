<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model {

    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'user_id',
        'product_id',
        'payment_method',
        'payment_token',
        'payment_url',
        'payment_status',
        'delivery',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function statusLabel() {
        switch ($this->payment_status) {
            case 0:
                return 'Pendente';
                break;
            case 1:
                return 'Aprovado';
                break;
            default:
                return '---';
        }
    }

    public function deliveryLabel() {
        switch ($this->payment_status) {
            case 0:
                return '<span class="badge bg-warning">Não entregue</span>';
                break;
            case 1:
                return '<span class="badge bg-success">Entregue</span>';
                break;
            default:
                return '---';
        }
    }
}
