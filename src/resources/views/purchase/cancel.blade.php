@extends('layouts.app')

@section('content')
<h1>購入がキャンセルされました</h1>
<p>もう一度お試しください。</p>
@if ($item)
    <a href="{{ route('purchase', ['item_id' => $item->id]) }}">購入画面に戻る</a>
@else
    <a href="{{ route('topPage') }}" class="back-btn">トップページへ戻る</a>
@endif
@endsection