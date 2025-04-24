@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

@if(session('message'))
<!-- ログイン成功 -->
    <div class="alert">
        <div class="alert-message">
            @if(Auth::check())
                {{ Auth::user()->name }} {{ session('message') }}
            @else
                {{ Auth::user()->name }} {{ session('message') }}
            @endif
        </div>
    </div>
@endif

<div class="top-view">
    <div class="list-link">
            @if(Auth::check())
                <a href="{{ route('topPage', ['name' => request('name')]) }}" class="link-title {{ request()->routeIs('topPage') ? 'active' : '' }}">おすすめ</a>
                <a href="{{ route('myList', ['name' => request('name')]) }}" class="link-title {{ request()->routeIs('myList') ? 'active' : '' }}">マイリスト</a>
            @else
                <a href="{{ route('nothing') }}"class="link-title {{ request()->routeIs('nothing') ? 'active' : '' }}">おすすめ</a>
                <a href="{{ route('nothing') }}" class="link-title {{ request()->routeIs('nothing') ? 'active' : '' }}">マイリスト</a>
            @endif
    </div>
</div>

@if(count($items) > 0)
    <div class="row-view">
        <div class="goods-view">

            <div class="goods-cards">
                @foreach($items as $item)
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

        </div>
    </div>
    <div class="pagination">
        {{ $items->links('vendor.pagination.bootstrap-4') }}
    </div>
@else
    <div class="search-result_noting">
        <p>検索結果がありません</p>
    </div>
@endif

@endsection