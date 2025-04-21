@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<p>以下のリンクをクリックして認証を完了してください。</p>
<a href="{{ route('auth.auth', ['email' => $email, 'onetime_token' => $onetime_token]) }}">
    認証リンク
</a>
@endsection