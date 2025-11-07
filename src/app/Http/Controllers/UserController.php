<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\TradeCommentRequest;

use App\Models\User;
use App\Models\Item;
use App\Models\Coment;
use App\Models\Evaluation;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\ItemFinishedMail;

class UserController extends Controller
{


    public function hold(Request $request)
    {

        $request->validate([
            'item_id' => 'required|integer',
            'message' => 'required|string',
            // 'message' => 'required|string|max:1000',
        ]);

        // $message = $request->message;

        // // 文字数制限：400文字まで
        // $maxLength = 400;
        // if (mb_strlen($message) > $maxLength) {
        //     // 先頭から400文字を切り取る
        //     $message = mb_substr($message, 0, $maxLength);
        // }

        $chat = Coment::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'item_id' => $request->item_id,
                'is_hold' => true, // 未送信保持
            ],
            [
                'content' => $request->message,
                'is_trading' => true,
                'is_read' => true, //既読にした方が未読が抽出しやすい為true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => '未送信メッセージを保存しました',
        ]);
    }


    public function finish(Item $item)
    {

        $item->update(['is_finished' => true]);
        // 出品者情報を JSON で返す（AJAXで確認可能）

        // 出品者にメール送信
        Mail::to($item->seller->email)->send(new ItemFinishedMail($item));
        return response()->json([
            'success' => true,
            'item' => $item,
            'seller' => $item->seller, // これでメール送信前に seller の email があるか確認できる
        ]);
        // JSONでレスポンス返却（JS側でモーダル表示に使う）
        return response()->json(['success' => true]);
    }

    public function evaluate(Request $request, Item $item)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
        ]);

        Evaluation::create([
            'item_id' => $item->id,
            'user_id' => $item->buyer_id == Auth::id() ? $item->user_id : $item->buyer_id, // 相手を評価
            'evoluter_id' => Auth::id(),
            'rate' => $request->rate,
        ]);

        // return redirect()->route('trade.show', $item->id)->with('success', '評価を送信しました！');
        return redirect('/');
    }


    public function update(Request $request, Coment $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if ($chat->user_id != Auth::id()) abort(403);

        $chat->content = $request->message;
        $chat->save();

        return redirect()->back(); // リロード
    }

    public function destroy(Coment $chat)
    {
        if ($chat->user_id != Auth::id()) abort(403);

        if ($chat->image) {
            $path = storage_path('app/public/comment_images/' . $chat->image);
            if (file_exists($path)) unlink($path);
        }

        $chat->delete();

        return redirect()->back(); // リロード
    }



    public function send(TradeCommentRequest $request, $item)
    {


        //バリデーションチェック外部
        // $request->validate([
        //     'message' => 'required|string|max:1000', // コメント必須
        //     'image' => 'nullable|image|max:2048',   // 画像は任意
        // ]);

        $chat = new Coment();
        $chat->user_id = Auth::id();
        $chat->item_id = $item;
        $chat->content = $request->message;
        $chat->is_trading = true;
        $chat->is_read = false;
        $chat->is_hold = false;


        //画像保存
        if ($request->hasFile('image')) {

            $filename = Str::uuid();
            $request->file('image')->move(storage_path('app/public/comment_images/'), $filename);
            $chat->image = $filename;
        }

        $chat->save();


        //未送信コメントがある場合は削除
        Coment::where('item_id', $item)
            ->where('user_id', $chat->user_id)
            ->where('is_hold', true)
            ->delete();

        return redirect()->back(); // リロードで反映
    }




    public function showTrade(Item $item)
    {
        if ($item->sold !== 1) {
            return redirect("/mypage");
        }


        $self_user = Auth::user();

        if ($self_user->id !== $item->user_id && $self_user->id !== $item->buyer_id) {
            return redirect("/mypage");
        }


        // 取引相手を特定（自分が出品者なら購入者、購入者なら出品者）
        $other_user = $item->user_id === $self_user->id
            ? $item->buyer
            : $item->seller;

        // 自分が関係している他の取引中商品を取得（取引完了前）
        $trade_items = Item::where(function ($query) use ($self_user) {
            $query->where('user_id', $self_user->id)
                ->orWhere('buyer_id', $self_user->id);
        })
            // ->where('is_finished', false)
            ->whereDoesntHave('ratings', function ($query) use ($self_user) {
                $query->where('evoluter_id', $self_user->id);
            })
            ->where('sold', true)
            ->where('id', '!=', $item->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        // dd($trade_items);


        // この商品の取引コメント（is_trading=1 のみ）
        // $chats = Coment::where('item_id', $item->id)
        //     ->where('is_trading', true)
        //     ->orderBy('created_at', 'asc')
        //     ->get()
        //     ->map(function ($chat) use ($self_user) {
        //         return (object)[
        //             'id' => $chat->id,
        //             'message' => $chat->content,
        //             'image' => $chat->image,
        //             'is_self' => $chat->user_id === $self_user->id,
        //             'user' => $chat->user,
        //         ];
        //     });

        // この商品の取引コメント（is_trading=1 のみ）
        $chats = Coment::where('item_id', $item->id)
            ->where('is_trading', true)
            ->where('is_hold', false)
            ->orderBy('created_at', 'asc')
            ->get();

        // 自分が未読のコメントを既読に更新
        $chats->where('user_id', '!=', $self_user->id) // 自分以外のコメントだけ
            ->where('is_read', false)
            ->each(function ($chat) {
                $chat->update(['is_read' => true]);
            });

        // 表示用に整形
        $chats = $chats->map(function ($chat) use ($self_user) {
            return (object)[
                'id' => $chat->id,
                'message' => $chat->content,
                'image' => $chat->image,
                'is_self' => $chat->user_id === $self_user->id,
                'user' => $chat->user,
            ];
        });


        //未送信コメントがある場合は表示
        $unsentComment = Coment::where('item_id', $item->id)
            ->where('user_id', $self_user->id)
            ->where('is_hold', true)
            ->first();

        $has_evaluated = Evaluation::where('item_id', $item->id)
            ->where('evoluter_id', $self_user->id)
            ->exists();
        // $other_user =        $item->buyer;
        // dd($other_user->user_img);
        return view('item-trade', compact(
            'item',
            'self_user',
            'other_user',
            'trade_items',
            'chats',
            'has_evaluated',
            'unsentComment'
        ));
    }


    //　商品取引ページ表示
    // public function showTrade(Item $item)
    // {
    //     // 　　未送信コメント保存機能GPT
    //     // 　　サイドバー　他の商品読み込み・・・mypage流用
    //     // 　　コメント表示・既読機能


    //     // 　　取引終了（購入者のみ）
    //     // 　　評価(モーダル？)


    //     // 　　コメント送信
    //     // 　　画像送信　SELL画面流用
    //     // 　　コメント編集　コメント自体の吹き出しが編集可能になって編集できるイメージ？
    //     // 　　コメント削除　確認いらん？いらんか・・・DELETE（POST）


    //     return view('item-trade');
    // }
    public function mypage()
    {
        $user_id = Auth::id();
        $sell_items = Item::where('user_id', Auth::id())->get();
        $buy_items = Item::where('buyer_id', Auth::id())->get();

        //取引中の商品 ログインユーザーが出品・購入＋取引未完了　新規コメント数をそれぞれカウント＋合計　更新順に商品をソート GPT! 合計数はブレード側のforeachで対応？
        //リロードしないと変更されないが・・・
        // $trade_items = Item::where('buyer_id', Auth::id())->get();

        // //ログインユーザーの評価を計算して表示
        // $evalutions = Evaluation::where('user_id', Auth::id())->get();
        // $rateSum = 0;
        // foreach ($evalutions as $evalution) {
        //     $rateSum += $evalution->rate;
        // }
        // //四捨五入？
        // $rates = $rateSum / $evalutions->count();

        // return view('mypage', compact('sell_items', 'buy_items', 'trade_items', 'rates'));
        // 取引中の商品（出品または購入、取引未完了）
        $trade_items = Item::where(function ($q) use ($user_id) {
            $q->where('user_id', $user_id)
                ->orWhere('buyer_id', $user_id);
        })
            ->where('sold', true)
            // ->where('is_finished', false)
            ->whereDoesntHave('ratings', function ($query) use ($user_id) {
                $query->where('evoluter_id', $user_id);
            })
            ->orderByDesc(
                Coment::select('created_at')
                    ->whereColumn('item_id', 'items.id')
                    ->where('is_trading', true)
                    ->latest()
                    ->limit(1)
            )
            ->get();

        // 各商品の未読コメント数を付与
        foreach ($trade_items as $item) {
            $item->unread_count = Coment::where('item_id', $item->id)
                ->where('is_trading', true)
                ->where('is_read', false)
                ->where('user_id', '!=', $user_id)
                ->count();
        }

        // 合計未読コメント数
        $total_unread = $trade_items->sum('unread_count');


        $avg_rate = Evaluation::where('user_id', $user_id)->avg('rate');
        $avg_rate = $avg_rate ? round($avg_rate) : 0; // 四捨五入、小数点なし

        return view('mypage', compact(
            'sell_items',
            'buy_items',
            'trade_items',
            'total_unread',
            'avg_rate'
        ));
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
