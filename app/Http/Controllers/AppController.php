<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Notebook;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {
    
    public function app() {

        $userId = Auth::id();
    
        $notebooks = Notebook::where('user_id', $userId)->pluck('id');
    
        $errorsCount = Answer::whereIn('notebook_id', $notebooks)
            ->whereHas('option', function($query) {
                $query->where('is_correct', false);
            })->count();
    
        $correctCount = Answer::whereIn('notebook_id', $notebooks)
            ->whereHas('option', function($query) {
                $query->where('is_correct', true);
            })->count();

        $questionsTodayCount = Answer::whereIn('notebook_id', $notebooks)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $totalQuestionsCount = Answer::whereIn('notebook_id', $notebooks)->count();

        $totalQuestions = Auth::user()->meta ?: 100;
        $progress = ($totalQuestionsCount / $totalQuestions) * 100;
    
        return view('app.app', [
            'errorsCount'           => $errorsCount,
            'correctCount'          => $correctCount,
            'questionsTodayCount'   => $questionsTodayCount,
            'totalQuestionsCount'   => $totalQuestionsCount,
            'progress'              => min($progress, 100),
        ]);
    }

}
