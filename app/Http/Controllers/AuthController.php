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
            'phone_number' => $request->phone_number,
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

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
           if(Auth::user()->role === 'Admin'){
                return redirect()->route('index-news-admin');
           }else{
            // để test trang admin , sau phân quyền sửa lại
                return redirect()->route('home');
           }
        }

        return back()->withErrors([
            'name' => 'Email hoặc mật khẩu không đúng',
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

        // Sửa dòng này:
        return redirect()->route('login-form')->with('success', 'Đã đăng xuất!');

        //view
    }
    public function showLogin()
    {
        return view('auth.login');
    }
    public function showRegister()
    {
        //view
    }
}