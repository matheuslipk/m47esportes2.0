<?php

namespace App\Http\Controllers\AuthAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;

class LoginAdminController extends Controller
{
    public function __construct(){
		$this->middleware('guest:web-admin');
	}

    public function showLoginForm(){
    	return view('auth.adminlogin');
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

        $authOk = Auth::guard('web-admin')->attempt($credenciais, $request->remember);

	     if ($authOk) {
            $request->session()->regenerate();
            $admin = Admin::where('email', $request->input('email'))->first();
            $adminSession = [
                'admin_id' => $admin->id,
                'email' => $admin->email,
            ];

            $request->session()->put('admin', $adminSession);
	     	return redirect('/admin/eventos');
	     }

	     return redirect()->back()->withInputs($request->only('email'));

    }

    public function logout(Request $request){
       Auth::guard('web-admin')->logout();

        $request->session()->invalidate();

        return redirect('/admin');
    }
}
