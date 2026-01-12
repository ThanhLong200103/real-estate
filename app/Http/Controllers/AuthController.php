<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone_number'=>$request->phone_number,
            'role'     => 'user',
            'status'   => 1,
        ]);

        Auth::login($user);


       //view
    }

    /**
     * LOGIN
     */


    public function login(LoginRequest $request)
    {

        if (Auth::attempt($request->validate())) {
            $request->session()->regenerate();


            //view
        }

        return back()->withErrors([
            'name' => 'Name hoặc mật khẩu không đúng',
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //view
    }
    public function showLogin(){
        // view
    }
    public function showRegister(){
        //view
    }
}
