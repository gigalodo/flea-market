@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">




@endsection

@section('content')

<div class="profile__content">
    <div class="profile__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form" action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form__group">
            <div class="form__group-content">
                <div class="profile__image-upload">

                    <img id="imagePreview" src="{{ asset('storage/profile_images/'.$user->user_img) }}" alt="{{$user->user_img}}" class="preview-img">
                    <label class="custom-file-upload">
                        画像を選択する
                        <input type="file" id="imageInput" name="user_img" class="file-input" accept="image/*" onchange="previewImage(event)">
                    </label>
                </div>
                <div class="form__error">
                    @error('user_img')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{old('name')??$user->name}}" />
                </div>

                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="post_code" value="{{old('post_code')??$user->post_code}}" />
                </div>
                <div class="form__error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{old('address')??$user->address}}" />
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{old('building')??$user->building}}" />
                </div>
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