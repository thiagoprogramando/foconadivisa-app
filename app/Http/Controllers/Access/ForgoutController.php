<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;

use App\Mail\Recovery;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgoutController extends Controller {
    
    public function forgout($code = null) {

        return view('forgout', [
            'code' => $code
        ]);
    }

    public function sendRecovery(Request $request) {

        $user = User::where('email', $request->email)->first();
        if ($user) {

            $token = $this->createCode($user);

            $send = Mail::to($user->email, $user->name)->send(new Recovery([
                'toName'    => $user->name,
                'toEmail'   => $user->email,
                'fromName'  => env('MAIL_FROM_NAME'),
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'subject'   => 'Recuperação de dados',
                'message'   => $token
            ]));

            return redirect()->back()->with('success', 'Verifique seu email, enviamos os dados para recuperação!');

        }

        return redirect()->back()->with('error', 'Nenhuma conta associada ao E-mail, verifique seus dados e tente novamente!');
    }

    private function createCode($user) {

        $token = strtoupper(Str::random(6));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
    
        return $token;
    }

    public function recoveryPassword(Request $request) {

        if ($request->password !== $request->confirmpassword) {
            return redirect()->back()->with('error', 'Senhas não coincidem!');
        }

        $tokenData = DB::table('password_reset_tokens')->where('token', $request->code)->first();
        if (!$tokenData) {
            return redirect()->back()->with('error', 'Código inválido. Tente novamente.');
        }

        $expirationTime = Carbon::parse($tokenData->created_at)->addMinutes(60);
        if (Carbon::now()->isAfter($expirationTime)) {
            return redirect()->route('recuperar-conta')->with('error', 'O código expirou. Solicite um novo.');
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        $user->password = Hash::make($request->password);
        if ($user->save()) {
            DB::table('password_reset_tokens')->where('email', $tokenData->email)->delete();
            return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Agora você pode fazer login.');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível completar a operação.');
    }

}
