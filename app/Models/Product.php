<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'photo',
        'file',
        'name',
        'description',
        'value',
        'status',
        'views'
    ];

    public function payments() {
        return $this->hasMany(Payment::class, 'product_id');
    }

    protected static function boot() {
        parent::boot();
    
        static::deleting(function ($product) {
            $product->payments()->delete();
        });
    }
}
