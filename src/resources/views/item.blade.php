@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endsection

@section('content')
<div class="item__content">
    <div class="item__image">
        <div class="image-wrapper">
            <img src="{{ asset('storage/product_images/'.$item->img) }}" class="item__image" alt="{{$item->img}}">
            @if($item->sold)
            <span class="sold-label">SOLD</span>
            @endif
        </div>
    </div>

    <div class="item__details">
        <h1 class="item__title">{{$item->name}}</h1>
        <p class="item__brand">{{$item->brand}}</p>
        <h2 class="item__price">{{"¥".number_format($item->price)." (税込)"}}</h2>

        <div class="item__actions">
            <div class="icon-with-label">
                <form action="/item/good/{{$item->id}}" method="post">
                    @csrf
                    @method('PUT')
                    <button type="submit" id="like_button" class="icon-button">
                        <i class="fa-solid fa-star"></i>
                    </button>
                    <input type="hidden" name="on_off" id="like_value" value="{{$like_button['check']}}">
                </form>
                <div class="icon-label">{{$like_button['count']}}</div>
            </div>

            <div class="icon-with-label">
                <a href="#coment_area" class="icon-button">
                    <i class="fa-solid fa-comment"></i>
                </a>
                <div class="icon-label">{{$coments->count()}}</div>
            </div>
        </div>

        <a href="/purchase/{{$item->id}}" class="item__purchase-link" @if ($item->sold || $item->user_id === Auth::id()) onclick="return false;" @endif>
            購入手続きへ
        </a>

        <div class="item__section">
            <h2>商品説明</h2>
            <p>{{$item->detail}}</p>
        </div>

        <div class="item__section">
            <h2>商品の情報</h2>
            <div class="item-info-block">
                <label class="item-info-label">カテゴリー</label>
                @foreach($item->categories as $category)
                <span class="tag">{{$category->category->content}}</span>
                @endforeach
            </div>
            <div class="item-info-block">
                <label class="item-info-label">商品の状態</label>
                <span>{{$item->condition->content}}</span>
            </div>
        </div>

        <div class="item__section" id="coment_area">
            <h2>コメント ({{$coments->count()}})</h2>
            @foreach($coments as $coment)
            <div class="coment__block">
                <img src="{{asset('storage/profile_images/'.$coment->user->user_img)}}" alt="{{$coment->user->user_img}}" class="user-img">
                <div class="coment__content">
                    <p class="coment__user">{{$coment->user->name}}</p>
                    <p>{{$coment->content}}</p>
                </div>
            </div>
            @endforeach
        </div>

        <form action="/item/{{$item->id}}" method="post" class="item__comment-form">
            @csrf
            <h3>商品へのコメント</h3>
            <div class="form__error">
                @error('content'){{ $message }}@enderror
            </div>
            <textarea name="content">{{old('content')}}</textarea>
            <button type="submit" class="comment__submit">コメントを送信する</button>
        </form>
    </div>
</div>

<script>
    const elem = document.getElementById("like_button");
    const check = document.getElementById("like_value");
    if (Number(check.value) === 1) {
        elem.style.color = "orange";
    }
</script>
@endsection