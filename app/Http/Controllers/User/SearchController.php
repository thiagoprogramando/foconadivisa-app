<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Notebook;
use App\Models\Subject;
use App\Models\Topic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller {
    
    public function search(Request $request) {

        $notebooks  = Notebook::where('name', 'LIKE', '%'.$request->search.'%')->where('user_id', Auth::user()->id)->get();
        $subjects   = Subject::where('name', 'LIKE', '%'.$request->search.'%')->where('description', 'LIKE', '%'.$request->search.'%')->get();
        $topics     = Topic::where('name', 'LIKE', '%'.$request->search.'%')->where('description', 'LIKE', '%'.$request->search.'%')->get();

        return view('app.Search.search', [
            'notebooks' => $notebooks,
            'subjects'  => $subjects,
            'topics'    => $topics,
        ]);
    }
}
