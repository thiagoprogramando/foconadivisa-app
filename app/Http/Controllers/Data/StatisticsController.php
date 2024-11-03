<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller {
    
    public function statistic() {

        $answers            = Answer::where('user_id', Auth::user()->id)->get();
        $answersCorrect     = Answer::where('user_id', Auth::user()->id)->where('status', 1)->get();
        $answersInCorrect   = Answer::where('user_id', Auth::user()->id)->where('status', 2)->get();
        $subjects           = $answers->pluck('question.subject')->unique('id');

        $subjectsWithCount = $subjects->map(function($subject) use ($answers) {
            $subject->answers_count = $answers->filter(function($answer) use ($subject) {
                return $answer->question->subject_id === $subject->id;
            })->count();
            return $subject;
        });

        return view('app.Data.statistics', [
            'answers'           => $answers,
            'answersCorrect'    => $answersCorrect,
            'answersInCorrect'  => $answersInCorrect,
            'subjects'          => $subjectsWithCount
        ]);
    }
}
