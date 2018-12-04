<?php

namespace App\Http\Controllers\AuthGerente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Gerente;

class LoginGerenteController extends Controller
{
    public function __construct(){
		$this->middleware('guest:gerente')->except('logout');
        $this->middleware('guest:web-admin')->except('logout');
        $this->middleware('guest')->except('logout');
	}

    public function showLoginForm(){
    	return view('auth.gerentelogin');
    }

    public function login(Request $request){
    	$this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credenciais = [
        	'email'=> $request->input('email'),
        	'password' => $request->input('password')
        ];

        $authOk = Auth::guard('gerente')->attempt($credenciais, $request->remember);

	     if ($authOk) {
            $request->session()->regenerate();
            $gerente = Gerente::where('email', $request->input('email'))->first();
            $adminSession = [
                'gerente_id' => $gerente->id,
                'email' => $gerente->email,
            ];

            $request->session()->put('gerente', $adminSession);
	     	return redirect('/gerente/apostas');
	     }

	     return redirect()->back()->withInputs($request->only('email'));

    }

    public function logout(Request $request){
       Auth::guard('gerente')->logout();

        $request->session()->invalidate();

        return redirect('/gerente/login');
    }
}
