<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Models\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/agent/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegForm()
    {
        return view('agent.auth.register');
    }

    protected function guard()
    {
        return Auth::guard('agent');
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $agents = new Agent();

        $this->validateInput($request);

        $agents = $this->data_input($agents, $input);

        $agents ->save();
        return redirect()->intended('agent/login');
    }

    private function validateInput($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }

    public function data_input($agents, $input)
    {
        $agents->name = $input['name'];
        $agents->email = $input['email'];
        $agents->password = bcrypt($input['password']);

        return $agents;
    }
}
