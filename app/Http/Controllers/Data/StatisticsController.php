<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;

use App\Models\Answer;
use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller {
    
    public function statistic() {

        $userId = Auth::user()->id;

        $answers            = Answer::where('user_id', $userId)->get();
        $answersCorrect     = Answer::where('user_id', $userId)->where('status', 1)->get();
        $answersInCorrect   = Answer::where('user_id', $userId)->where('status', 2)->get();

        $subjects = Subject::where('type', 1)
            ->where(function ($query) use ($answers, $userId) {
                $query->whereHas('questions.answers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->orWhereHas('topics.questions.answers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            })->get();

        $subjectsWithStats = $subjects->map(function ($subject) use ($answers) {

            $relatedSubjectIds = $subject->topics()->pluck('id')->toArray();
            $relatedSubjectIds[] = $subject->id;

            $relatedAnswers = $answers->filter(function ($answer) use ($relatedSubjectIds) {
                return in_array($answer->question->subject_id, $relatedSubjectIds);
            });

            $subject->answers_correct = $relatedAnswers->where('status', 1)->count();
            $subject->answers_incorrect = $relatedAnswers->where('status', 2)->count();
            $subject->answers_count = $relatedAnswers->count();

            return $subject;
        });

        $subjectsWithStats = $subjectsWithStats->filter(function ($subject) {
            return $subject->answers_count > 0;
        });

        return view('app.Data.statistics', [
            'answers'           => $answers,
            'answersCorrect'    => $answersCorrect,
            'answersInCorrect'  => $answersInCorrect,
            'subjects'          => $subjectsWithStats
        ]);
    }
}
