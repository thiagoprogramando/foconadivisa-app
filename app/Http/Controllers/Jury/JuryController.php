<?php

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Http\Request;

class JuryController extends Controller {
    
    public function juries(Request $request) {

        $query = Jury::orderBy('name', 'asc');

        if(!empty($request->name)) {
            $query->where('name', 'LIKE', '%'.$request->name.'%');
        }

        $juries = $query->get();

        return view('app.Jury.list-jury', [
            'juries' => $juries,
        ]);
    }

    public function createJury(Request $request) {

        $jury       = new Jury();
        $jury->name = $request->name;
        if ($jury->save()) {
            return redirect()->back()->with('success', 'Banca adicionada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível adicionar a banca, tente novamente mais tarde!');
    }

    public function updateJury(Request $request) {

        $jury       = Jury::find($request->id);
        $jury->name = $request->name;
        if ($jury->save()) {
            return redirect()->back()->with('success', 'Banca atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível atualizar a banca, tente novamente mais tarde!');
    }

    public function deleteJury(Request $request) {

        $jury       = Jury::find($request->id);
        if ($jury && $jury->delete()) {
            return redirect()->back()->with('success', 'Banca excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível excluir a banca, tente novamente mais tarde!');
    }
}
