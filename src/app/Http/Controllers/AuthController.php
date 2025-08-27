<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\Condition;
use App\Models\LikeButton;
use App\Models\Coment;


use Illuminate\Support\Str;


use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //


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
            // 認証成功 → セッション確立済み
            return redirect('/');
        }

        // 認証失敗 → 元の画面に戻し、エラー表示
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}
