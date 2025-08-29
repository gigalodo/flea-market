<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function authenticate(RegisterRequest $request)
    {
        $data = $request->validated();

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        return redirect('/mypage/profile');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}
