<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulated extends Model {

    use HasFactory;

    protected $table = 'simulations';

    protected $fillable = [
        'name',
        'description',
        'value',
        'date_start',
        'date_end'
    ];
}
