<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;

use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\Condition;
use App\Models\LikeButton;
use App\Models\Coment;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function index()
    {

        $recommend_items = Item::where('user_id', '!=', Auth::id())->get();

        $likes = LikeButton::where('user_id', Auth::id())->with('item')->get();
        $mylist_items = $likes->map(fn($like) => $like->item);

        return view('index', compact('recommend_items', 'mylist_items'));
    }

    public function serchItem(Request $request)
    {
        $search_word = $request->input('search_word');
        $recommend_items = Item::where('name', 'like', '%' . $search_word . '%')->where('user_id', '!=', Auth::id())->get();

        $likes = LikeButton::where('user_id', Auth::id())
            ->whereHas('item', function ($query) use ($search_word) {
                $query->where('name', 'like', '%' . $search_word . '%');
            })
            ->with('item')
            ->get();
        $mylist_items = $likes->map(fn($like) => $like->item);

        return view('index', compact('recommend_items', 'mylist_items', 'search_word'));
    }

    public function bindAddress(Item $item)
    {
        $user = Auth::user();
        return view('edit-address', compact('item', 'user'));
    }


    public function storeAddress(AddressRequest $request, Item $item)
    {
        $address = $request->only(['post_code', 'address', 'building']);
        session()->flash('address', $address);

        return redirect()->route('purchase.show', ['item' => $item->id]);
    }

    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }

    public function storeSell(ExhibitionRequest $request)
    {
        $data = $request->only('name', 'brand', 'price', 'detail', 'condition_id');
        $data += array('user_id' => Auth::id());
        $data += array('sold' => 0);

        $file = $request->file('img');

        if ($file) {
            $filename = Str::uuid();
            $file->move(storage_path('app/public/product_images/'), $filename);
            $data += array('img' => $filename);
        }

        $item = Item::create($data);

        $data = array('item_id' => $item->id, 'category_id' => 1);
        $categories = $request->input('categories');
        foreach ($categories as $category) {
            $data['category_id'] = $category;
            CategoryItem::create($data);
        }

        return redirect('/');
    }

    public function bindItem(Item $item)
    {
        $like_check = LikeButton::where('item_id', $item->id)->Where('user_id', Auth::id())->first() ? 1 : 0;
        $like_count = LikeButton::where('item_id', $item->id)->count();
        $like_button = [
            'check' => $like_check,
            'count' => $like_count
        ];

        $coments = Coment::where('item_id', $item->id)->with('user')->get();

        return view('item', compact('item', 'coments', 'like_button'));
    }

    public function bindPurchase(Item $item)
    {
        $user = Auth::user();
        $address = [
            'post_code' => $user->post_code,
            'address'   => $user->address,
            'building'  => $user->building,
        ];

        return view('purchase', compact('item', 'address'));
    }

    public function storePurchase(PurchaseRequest $request, Item $item)
    {
        $data = $request->all();
        $data += array('buyer_id' => Auth::id());
        $data += array('sold' => 1);

        Item::find($item->id)->update($data);

        return redirect('/');
    }

    public function storeComent(CommentRequest $request, Item $item)
    {
        $data = $request->only('content');
        $data += array('user_id' => Auth::id());
        $data += array('item_id' => $item->id);

        Coment::create($data);

        return redirect('/item/' . $item->id);
    }

    public function storeLikeButton(Request $request, Item $item)
    {
        $on_off = $request->input('on_off');
        $query = LikeButton::where('item_id', $item->id)->Where('user_id', Auth::id())->first();
        if ($on_off) {
            if ($query) {
                $query->delete();
            }
        } else {
            if (!$query) {
                LikeButton::firstOrCreate([
                    'user_id' => Auth::id(),
                    'item_id' => $item->id
                ]);
            }
        }

        return redirect()->back();
    }
}
