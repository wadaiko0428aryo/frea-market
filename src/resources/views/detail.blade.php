@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="content-left">
    <div class="item-img">
        <img src="{{ Str::startsWith($item->image, 'items/') ? asset('storage/' . $item->image) : asset('images/' . basename($item->image)) }}" alt="商品画像" class="item-image">
    </div>
</div>
<div class="content-right">
    <div class="content-right_view">
        <div class="item-name price">
            <div class="item-name">
                <h2>{{ $item->name }}</h2>
            </div>
            <div class="brand-name">
                <span class="brand-name_label">
                    ブランド名
                </span>
                <span class="brand-name_data">
                    {{ $item->brand }}
                </span>
            </div>
            <div class="item-price">
                <span class="¥">¥</span>
                <span class="item-price_data">{{ $item->price }}</span>
                <span class="express">(税込)</span>
            </div>

            <div class="icon-count">
                <form action="{{ route('toggleLike', ['item_id' => $item->id]) }}" method="post">
                    <div class="icon-like_count">
                        <div class="toggleLike">
                            <button class="like-btn" type="submit" data-item-id="{{ $item->id }}">
                                <!-- 初期状態で空のハートとカウント 0 -->
                                <p class="like-icon">
                                    @if($item->isLikedByUser())
                                        ⭐️
                                    @else
                                        ☆
                                    @endif
                                </p>
                                <p class="like-count" data-item-id="{{ $item->id }}">
                                    {{ $item->likes()->count() }}
                                </p>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="comment">
                    <img src="{{ asset('images/comment.png') }}" alt="吹き出しの画像" class="comment-count_img">
                    <div class="comment-count_number">
                        {{ $item->comments->count() }}
                    </div>
                </div>
            </div>

        </div>

        @if(Auth::check())
        <div class="buy-btn">
            @if(! $item->is_sold)
                <a href="{{ route('purchase' ,  ['item_id' => $item->id]) }}" class="buy-btn_link">
                    購入手続きへ
                </a>
            @else
                <a class="buy-btn_link">購入済み</a>
            @endif
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
            <textarea name="description" id="description" class="description-input" readonly>{{ $item->description }}</textarea>
        </div>

        <div class="item-information">
            <div class="item-label">
                商品の情報
            </div>
            <div class="information-group">
                <span class="information-label">
                    カテゴリー
                </span>
                <span class="category information-data">
                    @foreach($item->categories as $category)
                        <span class="category-data">{{ $category->category }}</span>
                    @endforeach
                </span>
            </div>
            <div class="information-group">
                <span class="information-label">
                    商品の状態
                </span>
                <span class="condition information-data">
                    {{ $item->condition }}
                </span>
            </div>
        </div>

        <div class="comment-group">
            <div class="comment-label">
                <span class="item-label">
                    コメント
                </span>
                <span class="comment-count">
                    ({{ $item->comments->count() }})
                </span>
            </div>

            <div class="comment-view">
                @foreach($item->comments as $comment)
                <div class="comment-box">
                    <div class="commenter-box">
                        <span class="commenter-icon">
                            <img src="{{ asset('storage/' . $comment->user->profile->image) }}" alt="{{ $comment->user->name }}のアイコン" class="profile-image">
                        </span>
                        <span class="commenter">
                            {{ $comment->user->name }}
                        </span>
                    </div>

                    <div class="comment-text">
                        {{ $comment->comment }}
                    </div>
                </div>
                @endforeach
            </div>

            @if(Auth::check())
            <div class="comment-send">
                <form action="{{ route('item.comment.store', ['item_id' => $item->id]) }}"method="post">
                @csrf
                    <div class="item-comment_label">
                        商品へのコメント
                    </div>
                    <textarea name="comment" id="comment" class="item-comment_send"></textarea>
                    <div class="error">
                        @error('comment')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="comment-send_btn">
                        <input class="comment-send_submit" type="submit" value="コメントを送信する">
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
                <a href="{{ route('topPage') }}" class="back-page">戻る</a>
            </div>
        </div>


    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    likeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // フォームの送信を防止

            console.log('Like button clicked'); // クリックイベントの確認
            const itemId = button.getAttribute('data-item-id');
            console.log('Item ID:', itemId); // itemIdの確認

            // いいねの状態を切り替える
            fetch(`/${itemId}/detail/like/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                }
            })
            .then(response => response.json())
            .then(data => {
                // サーバーから返された新しいカウントを表示
                const likeCountElement = button.querySelector('.like-count');
                likeCountElement.textContent = data.likeCount;

                // アイコンの切り替え
                const likeIconElement = button.querySelector('.like-icon');
                if (data.liked) {
                    likeIconElement.textContent = '⭐️'; // ⭐️に切り替え
                } else {
                    likeIconElement.textContent = '☆'; // ☆に戻す
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

@endsection
