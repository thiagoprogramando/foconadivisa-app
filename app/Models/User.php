<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'photo',
        'name',
        'cpfcnpj',
        'phone',

        'status',
        'plan',
        'type',
        'meta',
        
        'email',
        'password',
        'code',
        'customer',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function firstName() {
        $nameParts = explode(' ', $this->name);
        return $nameParts[0];
    }

    public function secondName() {
        $nameParts = explode(' ', $this->name);
        return isset($nameParts[1]) ? $nameParts[1] : '';
    }

    public function labelPlan() {
        return $this->belongsTo(Plan::class, 'plan');
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function typeLabel() {
        switch ($this->type) {
            case 0:
                return 'Cliente';
                break;
            case 1:
                return 'Administrador';
                break;
            case 2:
                return 'Colaborador';
                break;
            default:
                return 'Cliente';
        }
    }
}
