@extends('layouts.app')

@section('content')
<h1>購入が成功しました！</h1>
<p>決済が完了しました。</p>
<a href="{{ route('myPage') }}">マイページに戻る</a>
@endsection