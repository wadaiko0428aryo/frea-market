@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('content')
<div class="profile-view">
    <div class="user-view">
        <div class="profile-icon">
            <img src="{{ asset('storage/' . $profile->image) }}" alt="{{ $user->name }}のアイコン" class="profile-image">
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

<div class="row-view">
    <div class="goods-cards">
        @foreach($purchasedItems as $purchase)
            <a href="{{ route('detail' , $purchase->item->id) }}" class="goods-card">
                <img class="goods-img" src="{{ asset($purchase->item->image) }}" alt="商品画像">
                <div class="goods-name">{{ $purchase->item->name }}</div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $purchasedItems->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

@endsection