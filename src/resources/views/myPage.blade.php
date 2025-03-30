@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('content')

@if(session('message'))

<div class="alert">
    <div class="alert-message">
        {{session('message')}}
    </div>
</div>
@endif

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
            <a href="{{ route('myPage') }}" class="sell-item_link item-link {{ request()->routeIs('myPage') ? 'active' : '' }}">
                出品した商品
            </a>
        </div>
        <div class="buy-item item">
            <a href="{{ route('purchaseList') }}" class="buy-item_link item-link {{ request()->routeIs('purchaseList') ? 'active' : '' }}">
                購入した商品
            </a>
        </div>
    </div>
</div>

<div class="goods-cards">
    @foreach($items as $item)
            <a href="{{ route('detail' , $item->id) }}" class="goods-card">
                <img src="{{ Str::startsWith($item->image, 'items/') ? asset('storage/' . $item->image) : asset('images/' . basename($item->image)) }}" alt="商品画像" class="goods-img">
                <div class="goods-name">{{ $item->name }}</div>
            </a>
    @endforeach
</div>

<div class="pagination">
    {{ $items->links('vendor.pagination.bootstrap-4') }}
</div>

@endsection