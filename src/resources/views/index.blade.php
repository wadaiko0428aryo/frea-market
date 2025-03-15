@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-view">
    <div class="list-link">
        <div class="link-title">
            @if(Auth::check())
                <a href="{{ route('topPage') }}" class="suggest">おすすめ</a>
                <a href="{{ route('myList') }}" class="myPage-link">マイリスト</a>
            @else
                <a href="{{ route('nothing') }}"class="suggest">おすすめ</a>
                <a href="{{ route('nothing') }}" class="myPage-link">マイリスト</a>
            @endif
        </div>
    </div>
</div>

@if(count($items) > 0)
    <div class="row-view">
        <div class="goods-view">
            <div class="goods-cards">
                @foreach($items as $item)
                <a href="{{ route('detail' , $item->id) }}" class="goods-card">
                    <img src="{{ asset($item->image) }}" alt="商品画像" class="goods-img">
                    <div class="goods-name">{{ $item->name }}</div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="pagination">
        {{ $items->links('vendor.pagination.bootstrap-4') }}
    </div>
@else
    <div class="search-result_noting">
        検索結果がありません
    </div>
@endif
@endsection