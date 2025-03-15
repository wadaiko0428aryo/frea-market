@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="content-left">
    <div class="item-img">
        <img src="{{ asset($item->image) }}" alt="loading" class="img">
    </div>
</div>
<div class="content-right">
    <div class="content-right_view">
        <div class="item-name price">
            <div class="item-name">
                <h2>{{ $item->name }}</h2>
            </div>
            <div class="brand-name">
                @if($item->brand)
                {{ $item->brand }}
                @else
                ブランドデータがはいっていません
                @endif
            </div>
            <div class="item-price">
                ¥{{ $item->price }}(税込)
            </div>
            <div class="icon-count">
                <div class="favorite">
                    <img src="{{ asset('images/star.png') }}" alt="星の画像" class="favorite-img">
                    <div class="favorite-count">
                        (例)2<!-- 商品の持つお気に入りカウントを表示 -->
                    </div>
                </div>
                <div class="comment">
                    <img src="{{ asset('images/comment.png') }}" alt="吹き出しの画像" class="comment-img">
                    <div class="comment-count">
                        (例)2<!-- 商品の持つコメントカウントを表示 -->
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::check())
        <div class="buy-btn">
            <a href="{{ route('purchase' ,  ['item_id' => $item->id]) }}" class="buy-btn_link">
                購入手続きへ
            </a>
        </div>
        @else
        <div class="buy-btn">
            <a href="{{ route('nothing') }}" class="buy-btn_link">
                購入手続きへ
            </a>
        </div>
        @endif

        <div class="item-description">
            <div class="item-label">
                商品説明
            </div>
            <input type="text" value="{{ $item->description }}" readonly class="description-input">
        </div>

        <div class="item-information">
            <div class="item-label">
                商品の情報
            </div>
            <div class="category-group">
                <label for="category" class="information-label">
                    カテゴリー
                </label>
                <div class="category">
                    カテゴリーデータが入る
                </div>
            </div>
            <div class="condition-group">
                <label for="condition" class="information-label">
                    商品の状態
                </label>
                <div class="condition">
                    {{ $item->condition }}
                </div>
            </div>

            <div class="comment-group">
                <div class="item-label">
                    コメント
                </div>
                <div class="comment-count">
                    (例) (1)  <!-- 商品のコメント数を表示 -->
                </div>
                <div class="comment-view">
                    <div class="commenter">
                        (例)admin<!-- commentしたアカウントのアイコンを表示 -->
                    </div>
                    <input type="text"readonly value="ここにコメントが入る" class="comment-input">
                </div>
                @if(Auth::check())
                <div class="comment-send">
                    <form action=""method="post">
                    @csrf
                        <div class="item-comment_label">
                            商品へのコメント
                        </div>
                        <textarea name="comment" id="comment" class="item-comment_send"></textarea>
                        <div class="comment-send_btn">
                            <button class="comment-send_submit" type="submit">コメントを送信する</button>
                        </div>
                    </form>
                </div>
                @else
                <div class="comment-send">
                        <div class="item-comment_label">
                            商品へのコメント
                        </div>
                        <textarea name="comment" id="comment" class="item-comment_send"></textarea>
                        <div class="comment-send_btn">
                            <a class="comment-send_submit" href="{{ route('nothing') }}" >コメントを送信する</a>
                        </div>
                    </form>
                </div>
                @endif

                <div class="back-btn">
                    <a href="{{ route('topPage') }}" class="back-topPage">戻る</a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
