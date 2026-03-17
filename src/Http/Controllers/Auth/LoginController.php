<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Bitsoftsol\LaravelAdministration\Models\User;
use Bitsoftsol\LaravelAdministration\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**

     * Write code on Method

     *

     * @return response()

     */

     public function login(Request $request)
     {
        $inputs = $request->all();
         $request->validate([

             'email_or_username' => 'required',

             'password' => 'required',

         ]);

        //  dd($request->all(), $user = User::where('email', $inputs['email_or_username'])->orWhere('username', $inputs['email_or_username'])->first(),
        //  Hash::check($inputs['password'], $user->password)
        // );

         if ($user  = User::where('email', $inputs['email_or_username'])->orWhere('username', $inputs['email_or_username'])->first())
         {
            if (Hash::check($inputs['password'], $user->password)) {
                if($user->is_superuser)
                {
                    Auth::login($user);
                    return redirect()->to('/admin');
                }
                return redirect()->to("/admin")->with('error', 'Your account do not have permissions to access dashboard.');

            }
         }

         return redirect()->to("/admin")->with('error', 'These credentials do not match our records.');

     }

    public function username()
    {
        return 'email_or_username';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email_or_username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->input('email_or_username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        return [
            $field => $request->input('email_or_username'),
            'password' => $request->input('password'),
        ];
    }
}
