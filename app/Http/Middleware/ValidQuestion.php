<?php

namespace App\Http\Middleware;

use App\Models\Question;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidQuestion {

    public function handle(Request $request, Closure $next): Response {

        Question::whereNull('question_text')
                ->orWhere('question_text', '')
                ->delete();

        return $next($request);
    }
}
