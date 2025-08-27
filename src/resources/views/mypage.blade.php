@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@php $user = Auth::user(); @endphp

<div class="mypage__content">
    <div class="mypage__header">
        <div class="mypage__profile">
            <img src="{{ asset('storage/profile_images/'.$user->user_img) }}" alt="{{$user->user_img}}">
            <h2>{{$user->name}}</h2>
        </div>
        <a class="profile__edit" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="mypage__tabs">
        <button onclick="switchTab('sell')" id="tab-sell">出品した商品</button>
        <button onclick="switchTab('buy')" id="tab-buy">購入した商品</button>
    </div>

    <div id="sellContent" class="mypage__grid">
        @foreach($sell_items as $item)
        <div class="mypage__card">
            <a href="/item/{{$item->id}}">
                <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}">
                <p>{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>

    <div id="buyContent" class="mypage__grid" style="display: none;">
        @foreach($buy_items as $item)
        <div class="mypage__card">
            <a href="/item/{{$item->id}}">
                <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}">
                <p>{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>

<script>
    function switchTab(tab) {
        const sell = document.getElementById('sellContent');
        const buy = document.getElementById('buyContent');
        const tabSell = document.getElementById('tab-sell');
        const tabBuy = document.getElementById('tab-buy');

        if (tab === 'sell') {
            buy.style.display = 'none';
            sell.style.display = 'grid';
            tabSell.classList.add('active');
            tabBuy.classList.remove('active');
        } else {
            buy.style.display = 'grid';
            sell.style.display = 'none';
            tabBuy.classList.add('active');
            tabSell.classList.remove('active');
        }

        history.pushState(null, '', `?page=${tab}`);
    }

    function initializeTab() {
        const page = new URLSearchParams(location.search).get('page');
        switchTab(page === 'buy' ? 'buy' : 'sell');
    }

    document.addEventListener('DOMContentLoaded', initializeTab);
</script>
@endsection