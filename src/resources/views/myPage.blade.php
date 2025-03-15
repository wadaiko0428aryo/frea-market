@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-view">
    <div class="user-view">
        <div class="profile-icon">
            <img src="" alt="{{ $user->name }}のアイコン" class="icon">
        </div>
        <div class="profile-user">
            {{ $user->name }}
        </div>
        <div class="profile-edit">
            <a href="{{ route('profile') }}" class="profile-edit_link">
                プロフィールを編集
            </a>
        </div>
    </div>
    <div class="profile-link">
        <div class="sell-item item">
            <a href="{{ route('myPage') }}" class="sell-item_link item-link">
                出品した商品
            </a>
        </div>
        <div class="buy-item item">
            <a href="{{ route('purchaseList') }}" class="buy-item_link item-link">
                購入した商品
            </a>
        </div>
    </div>
</div>

<div class="item-view">
出品した商品
</div>
@endsection