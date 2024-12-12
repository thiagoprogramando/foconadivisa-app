<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Notebook;
use App\Models\Question;
use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller {
    
    public function search(Request $request) {

        $notebooks  = Notebook::where('user_id', Auth::user()->id)->get();
        $questions  = Question::where('question_text', 'LIKE', '%'.$request->search.'%')->get();

        return view('app.Search.search', [
            'notebooks' => $notebooks,
            'questions' => $questions
        ]);
    }
}
