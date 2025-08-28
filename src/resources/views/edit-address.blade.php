@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit-address.css') }}">
@endsection

@section('content')

<div class="address-form__content">
    <div class="address-form__heading">
        <h1 class="address-form__heading-title">住所の変更</h1>
    </div>
    <form class="address-form__form" action="/purchase/address/{{$item->id}}" method="POST">
        @csrf

        <div class="address-form__group">
            <div class="address-form__group-title">
                <label for="post_code" class="address-form__label">郵便番号</label>
            </div>
            <div class="address-form__group-content">
                <input id="post_code" type="text" name="post_code"
                    value="{{ old('post_code') ?? $user->post_code }}"
                    class="address-form__input" />
                <div class="address-form__error">
                    @error('post_code') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="address-form__group">
            <div class="address-form__group-title">
                <label for="address" class="address-form__label">住所</label>
            </div>
            <div class="address-form__group-content">
                <input id="address" type="text" name="address"
                    value="{{ old('address') ?? $user->address }}"
                    class="address-form__input" />
                <div class="address-form__error">
                    @error('address') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="address-form__group">
            <div class="address-form__group-title">
                <label for="building" class="address-form__label">建物名</label>
            </div>
            <div class="address-form__group-content">
                <input id="building" type="text" name="building"
                    value="{{ old('building') ?? $user->building }}"
                    class="address-form__input" />
                <div class="address-form__error">
                    @error('building') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="address-form__button">
            <button class="address-form__button--submit" type="submit">更新する</button>
        </div>

    </form>
</div>

@endsection