<?php

namespace App\Providers;

use App\Models\Notebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class Notebooks extends ServiceProvider {

    public function register(): void {
        
    }

    public function boot(): void {
        View::composer('*', function ($view) {
            
            if(Auth::check()) {

                $myNotebooks = Notebook::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
                $view->with(['myNotebooks' => $myNotebooks]);
            }
        });
    }
}
