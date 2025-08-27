@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="item__content">
    <div class="tab__menu">
        <button id="tab-recommend" onclick="switchTab('recommend')">おすすめ</button>
        <button id="tab-mylist" onclick="switchTab('mylist')">マイリスト</button>
    </div>

    <div id="recommendItems" class="item__list">
        @foreach($recommend_items as $item)
        <div class="item__card">
            <a href="/item/{{$item->id}}">
                <div class="image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}" class="item__image">
                    @if($item->sold)
                    <span class="sold-label">
                        SOLD
                    </span>
                    @endif
                </div>
                <p class="item__name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>

    <div id="mylistItems" class="item__list" style="display: none;">
        @foreach($mylist_items as $item)
        <div class="item__card">
            <a href="/item/{{$item->id}}">
                <div class="image-wrapper">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{$item->img}}" class="item__image">
                    @if($item->sold)
                    <span class="sold-label">
                        SOLD
                    </span>
                    @endif
                </div>
                <p class="item__name">{{$item->name}}</p>
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