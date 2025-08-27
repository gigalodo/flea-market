@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit-address.css') }}">
@endsection

@section('content')

<div class="contact-form__content">
    <div class="contact-form__heading">
        <h2>住所の変更</h2>
    </div>
    <form class="form" action="/purchase/address/{{$item->id}}" method="POST">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <label for="post_code" class="form__label--item">郵便番号</label>
            </div>
            <div class="form__group-content">
                <input id="post_code" type="text" name="post_code" value="{{ old('post_code') ?? $user->post_code }}" class="form__input--text" />
                <div class="form__error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <label for="address" class="form__label--item">住所</label>
            </div>
            <div class="form__group-content">
                <input id="address" type="text" name="address" value="{{ old('address') ?? $user->address }}" class="form__input--text" />
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <label for="building" class="form__label--item">建物名</label>
            </div>
            <div class="form__group-content">
                <input id="building" type="text" name="building" value="{{ old('building') ?? $user->building }}" class="form__input--text" />
                <div class="form__error">
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection