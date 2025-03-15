@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="purchase-right">
    <div class="purchase-img_card">
        <div class="purchase-img">
            <img src="{{ asset($item->image) }}" alt="loading" class="purchase-item_img">
        </div>
        <div class="purchase-name">
            {{ $item->name }}
        </div>
        <div class="purchase-price">
            ¥ {{ $item->price }}
        </div>
    </div>
    <form action="" method="post">
        @csrf
        <div class="purchase-method purchase-group">
            <div class="purchase-label">
                支払い方法
            </div>
            <select name="purchase" id="purchase" class="purchase-method_select">
                <option value="">選択してください</option>
                <option value="">コンビニ払い</option>
                <option value="">カード払い</option>
            </select>
        </div>
        <div class="purchase-address purchase-group">
            <div class="purchase-label">
                配送先
            </div>
            〒<input type="text" name="post" id="post" readonly  value="{{ $profile->post }}" class="profile-data">
            <input type="text" name="address" id="address" readonly value="{{ $profile->address }}" class="profile-data">
            <input type="text" name="building" id="building" readonly value="{{ $profile->building }}" class="profile-data">
            <a href="{{ route('address' , ['item_id' => $item->id]) }}" class="address-change_link">
                変更する
            </a>
        </div>
        <div class="select-result">
            <div class="select-result_group">
                <span class="result-label">商品代金</span>
                <span class="result-value">¥{{ $item->price }}</span>
            </div>
            <div class="select-result_group">
                <span class="result-label">支払い方法</span>
                <span class="result-value">selectで選択した値を表示</span>
            </div>
            <div class="purchase-btn">
                <button class="purchase-submit" type="submit">購入する</button>
            </div>
        </div>
    </form>
</div>
@endsection