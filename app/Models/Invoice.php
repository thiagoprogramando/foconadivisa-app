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
}
