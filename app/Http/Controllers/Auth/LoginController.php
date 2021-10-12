<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm(Request $request)
    {
        if (!Auth::check()){
            return view('auth.login');
        }
    }

    public function Login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            if (Auth::user()->ativo){
                return redirect()->intended('home');
            }else{
                Auth::logout();
                return view('auth.login', [
                    'error' => 'Usuário Inativo'
                ]);
            }

        }else{
            return view('auth.login', [
                'error' => 'Usuário e/ou senha inválidos.'
            ]);
        }
    }

    public function logout(){
        Auth::logout();

        return redirect('/');
        /*return view('auth.login', [

        ]);*/
    }
}
