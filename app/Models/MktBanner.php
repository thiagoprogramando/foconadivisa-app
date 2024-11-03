<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MktBanner extends Model {

    use HasFactory;

    protected $table = "mkt_banner";

    protected $fillable = [
        'name',
        'description',
        'file'
    ];
}
