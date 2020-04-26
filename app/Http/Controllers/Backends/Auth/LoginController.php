<?php

namespace App\Http\Controllers\Backends\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    /**
     * Login login
     * @param Request $request
     * @return type
     */
    public function showLogin(Request $request)
    {
        if (!Auth::check()) {
            return view('backends.auths.login', ['request' => $request]);
        }
        return redirect()->route('dashboard');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'username' => $request->{$this->username()},
            'password' => $request->password,
            'is_delete' => 0
        ];
    }

    /**
     * Login login
     * @param Request $request
     * validation of App\Http\Requests\Admin\AdminUserRequest
     * @return type
     */
    public function Login(LoginRequest $request)
    {
        $authAdmin = Auth::attempt([
            'name' => $request->name,
            'password' => $request->password,
            'is_active' => 1,
            'is_delete' => 1 // if want to check 0 for user active
        ]);
        //if login success
        if ($authAdmin) {
            Session::forget('login');
            return redirect()->route('dashboard');
        }
        return Redirect::route('login', [
            'request' => $request
        ])
        ->with('login', __('validation.auth'));
    }

    public function Logout()
    {
        Auth::logout();
        return Redirect::route('login');
    }
}