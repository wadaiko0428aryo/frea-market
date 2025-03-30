@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/noting.css') }}">
@endsection

@section('content')
<div class="noting-content">
    <div class="login-alert">
        このリンクを閲覧するにはログインする必要があります。
    </div>
    <div class="link">
        <a href="{{ route('login') }}" class="btn">
            ログイン画面へ
        </a>
        <a href="{{ route('topPage') }}" class="btn">
            トップページに戻る
        </a>
    </div>
</div>


@endsection