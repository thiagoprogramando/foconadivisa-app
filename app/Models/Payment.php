<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    use HasFactory;

    protected $table = 'product_payment';

    protected $fillable = [
        'product_id',
        'method',
        'installments',
    ];

    public function methodLabel() {
        switch ($this->method) {
            case 'CREDIT_CARD':
                return 'Cartão de Crédito';
                break;
            case 'PIX':
                return 'PIX';
                break;
            case 'BOLETO':
                return 'BOLETO';
                break;
            default:
                return 'PIX';
        }
    }
}
