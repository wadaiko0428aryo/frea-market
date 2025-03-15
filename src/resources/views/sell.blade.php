@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-content">
    <h2 class="sell-title">
        商品の出品
    </h2>
<form action="" method="post">
    @csrf
    <div class="sell-item_img">
        <div class="sell-item_label">
            商品画像
        </div>
        <div class="sell-item_input">
            <input type="file" name="image" id="image"  class="item-input">
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
            @foreach($categories as $category)
                <div class="categories-box">
                    <input type="checkbox" id="category" name="category" value="{{ $category->category }}" class="categories-checkbox">
                    <option value="{{ $category->category }}">{{ $category->category }}</option>
                </div>
            @endforeach
        </div>

        <div class="sell-item_group">
            <div class="sell-item_label">
                商品の状態
            </div>
            <div class="sell-item_input">
                <select name="condition" id="condition" class="item-input">
                <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition }}">{{ $condition }}</option>
                    @endforeach
                </select>
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
        </div>

        <div class="sell-item_group">
            <div class="sell-item_label">
                ブランド名
            </div>
            <div class="sell-item_input">
                <input type="text" name="brand" value="{{ old('brand') }}" id="brand" class="item-input">
            </div>
        </div>

        <div class="sell-item_group">
            <div class="sell-item_label">
                商品の説明
            </div>
            <div class="sell-item_input">
                <textarea  name="description" value="{{ old('description') }}" id="description" class="item-input"></textarea>
            </div>
        </div>

        <div class="sell-item_group">
            <div class="sell-item_label">
                販売価格
            </div>
            <div class="sell-item_input">
                <span class="dollar">¥</span>
                <input type="text" name="price" value="{{ old('price') }}" id="price" class="item-input" >
            </div>
        </div>
        <div class="sell-btn">
            <input type="submit" class="sell-btn_submit" value="出品する">
        </div>
    </div>
</form>

</div>
@endsection