@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="form__content">
    <h1 class="form__title">商品の出品</h1>

    <form class="form" action="/sell" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="form__group">
            <label class="form__label">商品画像</label>
            <div class="form__image-upload">
                <label class="image-upload-box" for="imageInput">
                    <span class="image-upload-text" id="uploadText">画像を選択する</span>
                    <img id="imagePreview" class="image-preview" src="" alt="商品画像プレビュー">
                </label>
                <input type="file" id="imageInput" name="img" class="file-input" accept="image/*" onchange="previewImage(event)">
            </div>
            <div class="form__error">
                @error('img') {{ $message }} @enderror
            </div>
        </div>

        <h2 class="form__subtitle">商品の詳細</h2>

        {{-- カテゴリ --}}
        <div class="form__group">
            <label class="form__label">カテゴリー</label>
            <div class="category__wrap">
                @foreach($categories->chunk(6) as $chunk)
                <div class="category__row">
                    @foreach($chunk as $category)
                    <input type="checkbox" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}" hidden>
                    <label for="category-{{ $category->id }}" class="category__label">{{ $category->content }}</label>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div class="form__error">
                @error('categories') {{ $message }} @enderror
            </div>
        </div>

        {{-- 状態 --}}
        <div class="form__group">
            <label class="form__label">商品の状態</label>
            <select class="form__select" name="condition_id">
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->content }}</option>
                @endforeach
            </select>
            <div class="form__error">
                @error('condition_id') {{ $message }} @enderror
            </div>
        </div>

        <h2 class="form__subtitle">商品名と説明</h2>

        {{-- 商品名 --}}
        <div class="form__group">
            <label class="form__label">商品名</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form__input">
            <div class="form__error">
                @error('name') {{ $message }} @enderror
            </div>
        </div>

        {{-- ブランド --}}
        <div class="form__group">
            <label class="form__label">ブランド名</label>
            <input type="text" name="brand" value="{{ old('brand') }}" class="form__input">
            <div class="form__error">
                @error('brand') {{ $message }} @enderror
            </div>
        </div>

        {{-- 商品説明 --}}
        <div class="form__group">
            <label class="form__label">商品の説明</label>
            <textarea name="detail" class="form__textarea">{{ old('detail') }}</textarea>
            <div class="form__error">
                @error('detail') {{ $message }} @enderror
            </div>
        </div>

        {{-- 価格 --}}
        <div class="form__group">
            <label class="form__label">販売価格</label>
            <input type="text" name="price" value="{{ old('price') }}" class="form__input">
            <div class="form__error">
                @error('price') {{ $message }} @enderror
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>
<script>
    const fileInput = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    const uploadText = document.getElementById('uploadText');
    const uploadBox = document.querySelector('.image-upload-box');

    // ファイル選択時の処理
    fileInput.addEventListener('change', function(event) {
        const file = fileInput.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            uploadText.style.display = 'none';
            uploadBox.classList.add('previewed');
        } else {
            preview.style.display = 'none';
            uploadText.style.display = 'inline-block';
            uploadBox.classList.remove('previewed');
        }
    });

    // 画像クリックで再度選択
    preview.addEventListener('click', function() {
        fileInput.value = ''; // クリック前にリセット
        fileInput.click();
    });
</script>



@endsection