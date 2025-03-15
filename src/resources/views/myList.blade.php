@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-view">
    <div class="link-title">
            <a href="{{ route('topPage') }}" class="suggest">おすすめ</a>
            <a href="{{ route('myList') }}" class="myPage-link">マイリスト</a>
    </div>
</div>
<div class="good-content">
    いいねした商品
</div>
@endsection