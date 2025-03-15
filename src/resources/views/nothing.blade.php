@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="login-alert">
    このリンクを閲覧するにはログインする必要があります。
</div>
<a href="{{ route('login') }}" class="login-link">
    ログイン画面へ
</a>
<a href="{{ route('topPage') }}" class="back-link">
    トップページに戻る
</a>
@endsection