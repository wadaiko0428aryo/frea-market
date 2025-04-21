@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mailCheck.css') }}">
@endsection

@section('content')
<div class="message-1">
    登録していただいたメールアドレスに認証メールを送付しました。
</div>
<div class="message-2">
    認証メールを完了してください
</div>

<a href="http://localhost:8025/" class="mail-link">
    認証はこちらから
</a>
<a href="" class="resend">認証メールを再送する</a>
@endsection

