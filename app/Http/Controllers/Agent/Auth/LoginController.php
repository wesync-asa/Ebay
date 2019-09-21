<?php

namespace App\Http\Controllers\Agent\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/agent/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('agent.auth.login');
    }

    protected function guard()
    {
        return Auth::guard('agent');
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/agent/login');
    }
}
