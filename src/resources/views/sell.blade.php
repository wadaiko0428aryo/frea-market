@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-content">
    <h2 class="sell-title">
        商品の出品
    </h2>
    <form action="{{ route('storeSell') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-item_img">
            <div class="sell-item_label">
                商品画像
            </div>
            <div class="sell-item_view">
                <div class="sell-item-img">
                    <img src="{{ isset($item) && $item->image ? asset('storage/' . $item->image) : asset('images/default.png') }}" alt="商品画像" id="previewImage" class="item-image">
                </div>
                <div class="sell-item_input">
                    <input type="file" id="imageInput" accept="image/*" name="image" id="image"  class="item-img_input">
                </div>
                <div class="label-box">
                    <label for="imageInput" class="image-select_btn">
                        画像を選択する
                    </label>
                    <div class="error">
                        @error('image')
                            {{$message}}
                        @enderror
                    </div>
                </div>
            </div>


        </div>

        <div class="sell-item_information">
            <h3 class="information-title">
                商品の詳細
            </h3>

            <div class="sell-item_categories">
                <div class="sell-item_label">
                    カテゴリー
                </div>
                <div class="categories-container">
                @foreach($categories as $category)
                    <div class="categories-box">
                        <input type="checkbox" id="category_{{ $category->id }}" name="category[]" value="{{ $category->category }}" class="categories-checkbox" {{ is_array(old('category')) && in_array($category->category, old('category')) ? 'checked' : '' }}>
                        <label for="category_{{ $category->id }}" class="categories-label">{{ $category->category }}</label>
                    </div>
                @endforeach
                </div>

                <div class="error">
                    @error('category')
                        {{$message}}
                    @enderror
                </div>
            </div>

            <div class="sell-item_group">
                <div class="sell-item_label">
                    商品の状態
                </div>
                <div class="sell-item_input">
                    <select name="condition" id="condition" class="item-input" >
                    <option value="">選択してください</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition }}" {{ old('condition') == $condition ? 'selected' : '' }}>{{ $condition }}</option>
                        @endforeach
                    </select>
                    <div class="error">
                        @error('condition')
                            {{$message}}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="sell-item_detail">
            <h3 class="detail-title">
                商品名と説明
            </h3>

            <div class="sell-item_group">
                <div class="sell-item_label">
                    商品名
                </div>
                <div class="sell-item_input">
                    <input type="text" name="name" value="{{ old('name') }}" id="name" class="item-input">
                </div>
                <div class="error">
                    @error('name')
                        {{$message}}
                    @enderror
                </div>
            </div>

            <div class="sell-item_group">
                <div class="sell-item_label">
                    ブランド名
                </div>
                <div class="sell-item_input">
                    <input type="text" name="brand" value="{{ old('brand') }}" id="brand" class="item-input">
                </div>
                <div class="error">
                    @error('brand')
                        {{$message}}
                    @enderror
                </div>
            </div>

            <div class="sell-item_group">
                <div class="sell-item_label">
                    商品の説明
                </div>
                <div class="sell-item_input">
                    <textarea  name="description" id="description" class="item-input">{{ old('description') }}</textarea>
                </div>
                <div class="error">
                    @error('description')
                        {{$message}}
                    @enderror
                </div>
            </div>

            <div class="sell-item_group">
                <div class="sell-item_label">
                    販売価格
                </div>
                <div class="sell-item_input price-input">
                    <span class="dollar">¥</span>
                    <input type="text" name="price" value="{{ old('price') }}" id="price" class="item-input item-input_price" >
                </div>
                <div class="error">
                    @error('price')
                        {{$message}}
                    @enderror
                </div>
            </div>
            <div class="sell-btn">
                <input type="submit" class="sell-btn_submit" value="出品する">
            </div>
        </div>
    </form>

</div>

    <script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImage = document.getElementById('previewImage');
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    </script>
@endsection