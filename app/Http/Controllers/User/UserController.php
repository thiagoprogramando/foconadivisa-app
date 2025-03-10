<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {
    
    public function profile(Request $request) {
        
        return view('app.User.profile');
    }

    public function users(Request $request) {
        
        $query = User::orderBy('name', 'desc');

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->email)) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if(!empty($request->cpfcnpj)) {
            $query->where('cpfcnpj', 'like', '%' . $request->cpfcnpj . '%');
        }

        $users = $query->paginate(30);

        return view('app.User.list-user', [
            'users' => $users
        ]);
    }

    public function updateProfile(Request $request) {

        $user = User::find($request->id);
        if (!$user) {
            return redirect()->back()->with('info', 'Não foi possível encontrar os dados do usuário!');
        }

        if(!empty($request->name)) {
            $data['name'] = $request->name;
        }

        if(!empty($request->cpfcnpj)) {
            $data['cpfcnpj'] = $request->cpfcnpj;
        }

        if(!empty($request->email)) {
            $data['email'] = $request->email;
        }

        if(!empty($request->phone)) {
            $data['phone'] = $request->phone;
        }

        if(!empty($request->meta)) {
            $data['meta'] = $request->meta;
        }

        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        if(!empty($request->type)) {
            $data['type'] = $request->type;
        }
        
        if(!empty($request->photo)) {

            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $data['photo'] = $path;
        }
        
        if($user && $user->update($data)) {
            return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível salvar às informações!');
    }

    public function deleteUser(Request $request) {
        
        $user = User::find($request->id);
        if($user && $user->delete()) {
            return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o usuário!');
    }

}
