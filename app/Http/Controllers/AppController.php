<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\MktBanner;
use App\Models\Product;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {
    
    public function app() {

        $userId = Auth::id();
    
        $errorsCount = Answer::where('user_id', $userId)
            ->where('status', 2)
            ->count();
    
        $correctCount = Answer::where('user_id', $userId)
            ->where('status', 1)
            ->count();

        $questionsTodayCount = Answer::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $totalQuestionsCount = Answer::where('user_id', $userId)->count();

        $totalQuestions = Auth::user()->meta ?: 100;
        $progress = ($totalQuestionsCount / $totalQuestions) * 100;

        $banners = MktBanner::orderBy('created_at', 'asc')->get();
        
        return view('app.app', [
            'errorsCount'           => $errorsCount,
            'correctCount'          => $correctCount,
            'questionsTodayCount'   => $questionsTodayCount,
            'totalQuestionsCount'   => $totalQuestionsCount,
            'progress'              => min($progress, 100),
            'banners'               => $banners,
        ]);
    }

}
