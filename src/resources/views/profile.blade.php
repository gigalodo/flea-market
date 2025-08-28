@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="profile">
    <h1 class="profile__heading-title">プロフィール設定</h1>

    <form class="profile__form" action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="profile__form-group">
            <div class="profile__image-upload">
                <img id="imagePreview" src="{{ asset('storage/profile_images/'.$user->user_img) }}" alt="{{$user->user_img}}" class="profile__preview-img">
                <label class="profile__file-label">
                    画像を選択する
                    <input type="file" name="user_img" class="profile__file-input" accept="image/*" onchange="previewImage(event)">
                </label>
            </div>
            <div class="profile__error">
                @error('user_img') {{ $message }} @enderror
            </div>
        </div>

        <div class="profile__form-group">
            <label class="profile__label">ユーザー名</label>
            <input type="text" name="name" value="{{ old('name') ?? $user->name }}" class="profile__input-text">
            <div class="profile__error">
                @error('name') {{ $message }} @enderror
            </div>
        </div>

        <div class="profile__form-group">
            <label class="profile__label">郵便番号</label>
            <input type="text" name="post_code" value="{{ old('post_code') ?? $user->post_code }}" class="profile__input-text">
            <div class="profile__error">
                @error('post_code') {{ $message }} @enderror
            </div>
        </div>

        <div class="profile__form-group">
            <label class="profile__label">住所</label>
            <input type="text" name="address" value="{{ old('address') ?? $user->address }}" class="profile__input-text">
            <div class="profile__error">
                @error('address') {{ $message }} @enderror
            </div>
        </div>

        <div class="profile__form-group">
            <label class="profile__label">建物名</label>
            <input type="text" name="building" value="{{ old('building') ?? $user->building }}" class="profile__input-text">
            <div class="profile__error">
                @error('building') {{ $message }} @enderror
            </div>
        </div>

        <button type="submit" class="profile__button-submit">更新する</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
</script>

@endsection