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
            <div>
                <h1 class="mypage__username">{{$user->name}}</h1>

                @if($avg_rate > 0)
                <div class="mypage__stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <=$avg_rate)
                        <span>★</span>
                        @else
                        <span class="empty">★</span>
                        @endif
                        @endfor

                </div>
                @endif
            </div>
        </div>
        <a class="mypage__edit-btn" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="mypage__tabs">
        <button class="mypage__tab-btn mypage__tab-btn--sell">出品した商品</button>
        <button class="mypage__tab-btn mypage__tab-btn--buy">購入した商品</button>
        <button class="mypage__tab-btn mypage__tab-btn--trade">
            取引中の商品
            @if($total_unread > 0)
            <span class="mypage__unread-total">{{$total_unread}}</span>
            @endif
        </button>
    </div>

    {{-- 出品した商品 --}}
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

    {{-- 購入した商品 --}}
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

    {{-- 取引中の商品 --}}
    <div class="mypage__grid mypage__grid--trade" style="display: none;">
        @foreach($trade_items as $item)
        <div class="mypage__item-card">
            <a href="/trade/{{$item->id}}">
                <div class="mypage-list__image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" class="mypage-list__image" alt="{{$item->img}}">
                    @if($item->unread_count > 0)
                    <span class="mypage-list__unread-badge">{{$item->unread_count}}</span>
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
        const tabTrade = document.querySelector('.mypage__tab-btn--trade');

        const gridSell = document.querySelector('.mypage__grid--sell');
        const gridBuy = document.querySelector('.mypage__grid--buy');
        const gridTrade = document.querySelector('.mypage__grid--trade');

        function switchTab(tab) {
            gridSell.style.display = tab === 'sell' ? 'grid' : 'none';
            gridBuy.style.display = tab === 'buy' ? 'grid' : 'none';
            gridTrade.style.display = tab === 'trade' ? 'grid' : 'none';

            tabSell.classList.toggle('active', tab === 'sell');
            tabBuy.classList.toggle('active', tab === 'buy');
            tabTrade.classList.toggle('active', tab === 'trade');

            history.pushState(null, '', `?page=${tab}`);
        }

        tabSell.addEventListener('click', () => switchTab('sell'));
        tabBuy.addEventListener('click', () => switchTab('buy'));
        tabTrade.addEventListener('click', () => switchTab('trade'));

        const page = new URLSearchParams(location.search).get('page');
        switchTab(['buy', 'trade'].includes(page) ? page : 'sell');
    });
</script>
@endsection