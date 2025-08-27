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

class UserController extends Controller
{
    //

    public function mypage()
    {

        $sell_items = Item::where('user_id', Auth::id())->get();
        $buy_items = Item::where('buyer_id', Auth::id())->get();
        // dd($buy_items);
        return view('mypage', compact('sell_items', 'buy_items'));
    }

    public function profile()
    {
        $user = User::where('id', Auth::id())->first();
        return view('profile', compact('user'));
    }


    public function storeProfile(ProfileRequest $request)
    {

        $data = $request->only('name', 'post_code', 'address', 'building');

        $file = $request->file('user_img');
        if ($file) {
            $filename = Str::uuid();
            $file->move(storage_path('app/public/profile_images/'), $filename);
            $data += array('user_img' => $filename);
        }

        User::find(Auth::id())->update($data);

        return redirect('/mypage');
    }
}
