<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller {
    
    public function login() {

        return view('login');
    }

    public function logon(Request $request) {

        $credentials = $request->only(['email', 'password']);
        $credentials['password'] = $credentials['password'];
        if (Auth::attempt($credentials)) {
            return redirect()->route('app');
        } else {
            return redirect()->back()->with('error', 'Credenciais inválidas!');
        }
    }

    public function register() {

        return view('register');
    }

    public function registrer(Request $request) {

        $user = new User();
        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->password  = bcrypt($request->password);
        $user->meta      = $request->meta;

        $credentials = $request->only(['email', 'password']);
        if($user->save() && Auth::attempt($credentials)) {
            
            return redirect()->route('app');
        }

        return redirect()->back()->with('error', 'Houve um problema, tente novamente mais tarde!');
    }

    public function logout() {

        Auth::logout();
        return redirect()->route('login');
    }

}
