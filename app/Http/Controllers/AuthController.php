<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('auth.sign-in');
    }

    public function onLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if(Auth::attempt(['username'=>$request->username,'password'=>$request->password])){
            $request->session()->regenerate();
            return redirect()->intended('/homes');
        } else {
            $error = [
                'title' => 'error',
                'icon' => 'error',
                'message' => 'Username or Password is Incorrect'
            ];
            return back()->with('error',$error);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
