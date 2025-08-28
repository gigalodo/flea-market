@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="product-list__content">
    <div class="product-list__tabs">
        <button id="tab-recommend" class="product-list__tab-button" onclick="switchTab('recommend')">おすすめ</button>
        <button id="tab-mylist" class="product-list__tab-button" onclick="switchTab('mylist')">マイリスト</button>
    </div>

    <div id="recommendItems" class="product-list__items">
        @foreach($recommend_items as $item)
        <div class="product-list__card">
            <a href="/item/{{$item->id}}">
                <div class="product-list__image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}" class="product-list__image">
                    @if($item->sold)
                    <span class="product-list__sold-label">SOLD</span>
                    @endif
                </div>
                <p class="product-list__name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>

    <div id="mylistItems" class="product-list__items" style="display: none;">
        @foreach($mylist_items as $item)
        <div class="product-list__card">
            <a href="/item/{{$item->id}}">
                <div class="product-list__image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}" class="product-list__image">
                    @if($item->sold)
                    <span class="product-list__sold-label">SOLD</span>
                    @endif
                </div>
                <p class="product-list__name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>

<script>
    function switchTab(tab) {
        const recommend = document.getElementById('recommendItems');
        const mylist = document.getElementById('mylistItems');
        const tabRecommend = document.getElementById('tab-recommend');
        const tabMylist = document.getElementById('tab-mylist');

        if (tab === 'recommend') {
            recommend.style.display = 'flex';
            mylist.style.display = 'none';
            tabRecommend.classList.add('active');
            tabMylist.classList.remove('active');
        } else {
            recommend.style.display = 'none';
            mylist.style.display = 'flex';
            tabRecommend.classList.remove('active');
            tabMylist.classList.add('active');
        }

        history.pushState(null, '', `?tab=${tab}`);
    }

    function initializeTab() {
        const tab = new URLSearchParams(location.search).get('tab');
        switchTab(tab === 'mylist' ? 'mylist' : 'recommend');
    }

    document.addEventListener('DOMContentLoaded', initializeTab);
</script>

@endsection