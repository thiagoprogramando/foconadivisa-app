<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;

use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    
    public function createComment(Request $request) {

        $comment                = new Comment();
        $comment->comment       = $request->comment;
        $comment->user_id       = Auth::user()->id;
        $comment->question_id   = $request->question_id;
        $comment->comment_id    = $request->comment_id;
        if($comment->save()) {
            return redirect()->back()->with('success', 'Comentário cadastrado com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao enviar comentário!');
    }

}
