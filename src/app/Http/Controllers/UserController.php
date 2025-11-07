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
        ]);

        $chat = Coment::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'item_id' => $request->item_id,
                'is_hold' => true, // 未送信時フラグ
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

        Mail::to($item->seller->email)->send(new ItemFinishedMail($item));

        return response()->json(['success' => true]);
    }

    public function evaluate(Request $request, Item $item)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
        ]);

        Evaluation::create([
            'item_id' => $item->id,
            'user_id' => $item->buyer_id == Auth::id() ? $item->user_id : $item->buyer_id,
            'evoluter_id' => Auth::id(),
            'rate' => $request->rate,
        ]);

        return redirect('/');
    }

    public function update(TradeCommentRequest $request, Coment $chat)
    {
        if ($chat->user_id != Auth::id()) abort(403);

        $chat->content = $request->message;
        $chat->save();

        return redirect()->back();
    }

    public function destroy(Coment $chat)
    {
        if ($chat->user_id != Auth::id()) abort(403);

        if ($chat->image) {
            $path = storage_path('app/public/comment_images/' . $chat->image);
            if (file_exists($path)) unlink($path);
        }

        $chat->delete();

        return redirect()->back();
    }

    public function send(TradeCommentRequest $request, $item)
    {
        $chat = new Coment();
        $chat->user_id = Auth::id();
        $chat->item_id = $item;
        $chat->content = $request->message;
        $chat->is_trading = true;
        $chat->is_read = false;
        $chat->is_hold = false;

        if ($request->hasFile('image')) {
            $filename = Str::uuid();
            $request->file('image')->move(storage_path('app/public/comment_images/'), $filename);
            $chat->image = $filename;
        }

        $chat->save();

        Coment::where('item_id', $item)
            ->where('user_id', $chat->user_id)
            ->where('is_hold', true)
            ->delete();

        return redirect()->back();
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

        $other_user = $item->user_id === $self_user->id
            ? $item->buyer
            : $item->seller;

        $trade_items = Item::where(function ($query) use ($self_user) {
            $query->where('user_id', $self_user->id)
                ->orWhere('buyer_id', $self_user->id);
        })
            ->whereDoesntHave('ratings', function ($query) use ($self_user) {
                $query->where('evoluter_id', $self_user->id);
            })
            ->where('sold', true)
            ->where('id', '!=', $item->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        $chats = Coment::where('item_id', $item->id)
            ->where('is_trading', true)
            ->where('is_hold', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $chats->where('user_id', '!=', $self_user->id)
            ->where('is_read', false)
            ->each(function ($chat) {
                $chat->update(['is_read' => true]);
            });

        $chats = $chats->map(function ($chat) use ($self_user) {
            return (object)[
                'id' => $chat->id,
                'message' => $chat->content,
                'image' => $chat->image,
                'is_self' => $chat->user_id === $self_user->id,
                'user' => $chat->user,
            ];
        });

        $unsentComment = Coment::where('item_id', $item->id)
            ->where('user_id', $self_user->id)
            ->where('is_hold', true)
            ->first();

        $has_evaluated = Evaluation::where('item_id', $item->id)
            ->where('evoluter_id', $self_user->id)
            ->exists();

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

    public function mypage()
    {
        $user_id = Auth::id();
        $sell_items = Item::where('user_id', Auth::id())->get();
        $buy_items = Item::where('buyer_id', Auth::id())->get();

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

        foreach ($trade_items as $item) {
            $item->unread_count = Coment::where('item_id', $item->id)
                ->where('is_trading', true)
                ->where('is_read', false)
                ->where('user_id', '!=', $user_id)
                ->count();
        }

        $total_unread = $trade_items->sum('unread_count');

        $avg_rate = Evaluation::where('user_id', $user_id)->avg('rate');
        $avg_rate = $avg_rate ? round($avg_rate) : 0;

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
