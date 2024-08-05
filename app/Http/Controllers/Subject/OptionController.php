<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller {
    
    public function createOption(Request $request) {

        $option                 = new Option();
        $option->question_id    = $request->question_id;
        $option->option_text    = $request->option_text;
        $option->is_correct     = $request->is_correct;

        if($option->save()) {
            return redirect()->back()->with('success', 'Resposta cadastrada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

    public function deleteOption(Request $request) {

        $option = Option::find($request->id);

        if($option && $option->delete()) {
            return redirect()->back()->with('success', 'Resposta excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir essa operação.');
    }

}
