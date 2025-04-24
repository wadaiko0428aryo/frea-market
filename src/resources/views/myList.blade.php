@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-view">
    <div class="list-link">
        <a href="{{ route('topPage', ['name' => request('name')]) }}" class="link-title {{ request()->routeIs('topPage') ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('myList', ['name' => request('name')]) }}" class="link-title {{ request()->routeIs('myList') ? 'active' : '' }}">マイリスト</a>
    </div>
</div>
<div class="good-content">
    @if($likedItems->isEmpty())
        <p class="not-liked">いいねした商品はありません</p>
    @else
        <div class="goods-cards">
            @foreach($likedItems as $item)
                @if(! $item->is_sold)
                    <a href="{{ route('detail' , $item->id) }}" class="goods-card">
                        <img src="{{ Str::startsWith($item->image, 'items/') ? asset('storage/' . $item->image) : asset('images/' . basename($item->image)) }}" alt="商品画像" class="goods-img">
                        <div class="goods-name">{{ $item->name }}</div>
                    </a>
                @else
                    <div class="goods-card sold">
                        <img src="{{ Str::startsWith($item->image, 'items/') ? asset('storage/' . $item->image) : asset('images/' . basename($item->image)) }}" alt="商品画像" class="goods-img">
                        <div class="goods-name">{{ $item->name }}</div>
                        <span class="sold-label">SOLD</span>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    <div class="pagination">
        {{ $likedItems->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection