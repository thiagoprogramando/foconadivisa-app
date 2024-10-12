<?php

namespace App\Http\Controllers\Mkt;

use App\Http\Controllers\Controller;

use App\Models\MktBanner;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller {
    
    public function banners(Request $request) {

        $banners = MktBanner::orderBy('created_at', 'asc')->get();
        return view('app.Mkt.list-banner', [
            'banners' => $banners
        ]);
    }

    public function createBanner(Request $request) {

        $banner              = new MktBanner();
        $banner->name        = $request->name;
        $banner->description = $request->description;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('mkt-banners', 'public');
            $banner->file = $path;
        }
        if ($banner->save()) {
            return redirect()->back()->with('success', 'Banner criado com sucesso!');
        } 

        return redirect()->back()->with('error', 'Erro ao salvar o banner.');
    }

    public function deleteBanner($id) {

        $banner = MktBanner::find($id);
        if($banner) {

            if ($banner->file) {
                Storage::delete('public/' . $banner->file);
            }

            if ($banner->delete()) {
                return redirect()->back()->with('success', 'Banner excluído com sucesso!');
            }

            return redirect()->back()->with('error', 'Não foram encontrado dados do Banner');
        }

        return redirect()->back()->with('error', 'Não foram encontrado dados do Banner');
    }

}
