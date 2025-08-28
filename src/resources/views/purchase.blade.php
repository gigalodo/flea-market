@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endsection

@section('content')

<div class="item__content">
    <div class="item__left">
        <form action="{{ '/purchase/'.$item->id }}" method="post">
            @csrf

            <div class="product__header">
                <div class="product__image">
                    <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="{{ $item->img }}">
                </div>

                <div class="product__info">
                    <h1 class="product__title">{{ $item->name }}</h1>
                    <h2 class="product__price">¥{{ number_format($item->price) }}</h2>
                </div>
            </div>

            <div class="payment__section">
                <h2 class="section__title">支払い方法</h2>
                @error('payment_method')
                <p class="form__error">{{ $message }}</p>
                @enderror
                <select id="paymentSelect">
                    <option value="0">コンビニ払い</option>
                    <option value="1">カード支払い</option>
                </select>
            </div>

            <div class="shipping__section">
                <h2 class="section__title">配送先</h2>
                @error('post_code')
                <p class="form__error">{{ $message }}</p>
                @enderror
                @error('address')
                <p class="form__error">{{ $message }}</p>
                @enderror

                <div class="shipping__change">
                    <a href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>

                @php
                $address = session('address', $address ?? []);
                @endphp
                <p>〒{{ $address['post_code'] ?? 'XXX-YYYY' }}</p>
                <input type="hidden" name="post_code" value="{{ $address['post_code'] ?? '' }}">
                <p>{{ $address['address'] ?? 'ここには住所と建物が入ります' }}</p>
                <input type="hidden" name="address" value="{{ $address['address'] ?? '' }}">
                <p>{{ $address['building'] ?? '' }}</p>
                <input type="hidden" name="building" value="{{ $address['building'] ?? '' }}">
            </div>
        </form>
    </div>

    <div class="item__right">
        <form action="{{ '/purchase/'.$item->id }}" method="post">
            @csrf
            <table class="summary__table">
                <tr>
                    <td>商品代金</td>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <td>支払い方法</td>
                    <td id="paymentDisplay">コンビニ払い</td>
                </tr>
            </table>

            <input type="hidden" name="payment_method" id="paymentMethod" value="0">
            <input type="hidden" name="post_code" value="{{ $address['post_code'] ?? '' }}">
            <input type="hidden" name="address" value="{{ $address['address'] ?? '' }}">
            <input type="hidden" name="building" value="{{ $address['building'] ?? '' }}">

            <button type="submit" class="purchase__button">購入する</button>
        </form>
    </div>
</div>

<script>
    const paymentSelect = document.getElementById('paymentSelect');
    const paymentDisplay = document.getElementById('paymentDisplay');
    const paymentMethod = document.getElementById('paymentMethod');

    paymentSelect.addEventListener('change', function() {
        paymentDisplay.textContent = paymentSelect.value == 0 ? "コンビニ払い" : "カード支払い";
        paymentMethod.value = paymentSelect.value;
    });
</script>
@endsection