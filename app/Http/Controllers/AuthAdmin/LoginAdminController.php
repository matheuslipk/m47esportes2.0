<?php

namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;

class LoginAdminController extends Controller
{
    public function __construct(){
		$this->middleware('guest:web-admin')->except('logout');
        $this->middleware('guest:gerente')->except('logout');
        $this->middleware('guest')->except('logout');
	}

    public function showLoginForm(){
    	return view('auth.adminlogin');
    }

    public function login(Request $request){
    	$this->validate($request, [
            'nickname' => 'required|string',
            'password' => 'required|string',
        ]);

        $credenciais = [
        	'nickname'=> $request->input('nickname'),
        	'password' => $request->input('password')
        ];

        $authOk = Auth::guard('web-admin')->attempt($credenciais, $request->remember);

	     if ($authOk) {
            $request->session()->regenerate();
            $admin = Admin::where('nickname', $request->input('nickname'))->first();
            $adminSession = [
                'admin_id' => $admin->id,
                'nickname' => $admin->nickname,
            ];

            $request->session()->put('admin', $adminSession);
	     	return redirect('/admin/eventos');
	     }

	     return redirect()->back()->withInputs($request->only('nickname'));

    }

    public function logout(Request $request){
       Auth::guard('web-admin')->logout();

        $request->session()->invalidate();

        return redirect('/admin');
    }

    public function username(){
        return "nickname";
    }
}
