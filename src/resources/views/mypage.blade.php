@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@php $user = Auth::user(); @endphp

<div class="mypage">
    <div class="mypage__header">
        <div class="mypage__profile">
            <img src="{{ asset('storage/profile_images/'.$user->user_img) }}" alt="{{$user->user_img}}">
            <h1 class="mypage__username">{{$user->name}}</h1>
        </div>
        <a class="mypage__edit-btn" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="mypage__tabs">
        <button class="mypage__tab-btn mypage__tab-btn--sell">出品した商品</button>
        <button class="mypage__tab-btn mypage__tab-btn--buy">購入した商品</button>
    </div>

    <div class="mypage__grid mypage__grid--sell">
        @foreach($sell_items as $item)
        <div class="mypage__item-card">
            <a href="/item/{{$item->id}}">
                <div class="mypage-list__image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" class="mypage-list__image" alt="{{$item->img}}">
                    @if($item->sold)
                    <span class="mypage-list__sold-label">SOLD</span>
                    @endif
                </div>
                <p class="mypage__item-name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>

    <div class="mypage__grid mypage__grid--buy" style="display: none;">
        @foreach($buy_items as $item)
        <div class="mypage__item-card">
            <a href="/item/{{$item->id}}">
                <div class="mypage-list__image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" class="mypage-list__image" alt="{{$item->img}}">
                    @if($item->sold)
                    <span class="mypage-list__sold-label">SOLD</span>
                    @endif
                </div>
                <p class="mypage__item-name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabSell = document.querySelector('.mypage__tab-btn--sell');
        const tabBuy = document.querySelector('.mypage__tab-btn--buy');
        const gridSell = document.querySelector('.mypage__grid--sell');
        const gridBuy = document.querySelector('.mypage__grid--buy');

        function switchTab(tab) {
            if (tab === 'buy') {
                gridBuy.style.display = 'grid';
                gridSell.style.display = 'none';
                tabBuy.classList.add('active');
                tabSell.classList.remove('active');
            } else {
                gridSell.style.display = 'grid';
                gridBuy.style.display = 'none';
                tabSell.classList.add('active');
                tabBuy.classList.remove('active');
            }
            history.pushState(null, '', `?page=${tab}`);
        }

        tabSell.addEventListener('click', () => switchTab('sell'));
        tabBuy.addEventListener('click', () => switchTab('buy'));

        const page = new URLSearchParams(location.search).get('page');
        switchTab(page === 'buy' ? 'buy' : 'sell');
    });
</script>
@endsection