<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class Notifications extends ServiceProvider {

    public function register(): void {
        
    }

    public function boot(): void {
        View::composer('*', function ($view) {
            
            if(Auth::check()) {
                $notifications = Notification::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
                $view->with(['notifications' => $notifications]);
            }
        });
    }
}
