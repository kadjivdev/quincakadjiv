<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        $loginType = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $request->merge([$loginType => $request->input('username')]);
        // dd('ok1');

        if (Auth::attempt($request->only($loginType, 'password')) && Auth::user()->is_active) {
                 return redirect()->intended('/');
        }

        return back()->withErrors(['username' => 'Identifiants incorrectes.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
