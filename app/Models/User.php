<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\DB;

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

    public function validadMonth() {

        $lastInvoice = $this->invoices()
            ->where('plan_id', $this->plan)
            ->orderBy('due_date', 'desc')
            ->first();

        if ($lastInvoice && $lastInvoice->payment_status == 0) {
            $daysRemaining = Carbon::now()->diffInDays(Carbon::parse($lastInvoice->due_date), false);
            if ($daysRemaining < 1) {
                return "É necessário <b>renovar</b> seu plano em <a href='".route('pagamentos')."'><b>Faturas</b></a>";
            }

            return "Seu teste grátis irá acabar em <a href='#'><b>" . abs($daysRemaining) + 1 . "</b></a> dias!";
        }
    
        if (!$lastInvoice) {
            return "Conheça os planos disponível para você! <a href='".route('planos')."'><b>Acessar Planos</b></a>";
        }

        $plan = $this->labelPlan;
        if (!$plan) {
            return 'Nenhum Plano Associado';
        }

        $activationDate = Carbon::parse($lastInvoice->created_at);
        switch ($plan->type) {
            case 1:
                $renewalDate = $activationDate->addMonth();
                break;
            case 2:
                $renewalDate = $activationDate->addYear();
                break;
            case 3:
                return 'Vitalício';
            default:
                return 'Plano inválido';
        }

        $today = Carbon::now();
        $daysRemaining = $today->diffInDays($renewalDate, false);

        if ($daysRemaining < 0) {
            return "Sua assinatura venceu há <a href='#'><b>" . abs($daysRemaining) . "</b></a> dias!";
        }

        return "Faltam <a href='#'><b>" . abs($daysRemaining) . "</b></a> dias para a renovação da sua Assinatura!";
    }

    public function hasUsedTrial() {
        return DB::table('trial_histories')
            ->where('user_id', $this->id)
            ->exists();
    }

}
