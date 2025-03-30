<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($itemId)
    {
        // 現在のユーザーを取得
        $user = auth()->user();

        // アイテムを取得
        $item = Item::findOrFail($itemId);

        // ユーザーが既にそのアイテムに「いいね」しているかどうかをチェック
        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // すでに「いいね」していたら、それを削除
            $like->delete();
            $liked = false;
        } else {
            // まだ「いいね」していなかったら、それを作成
            $item->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        // 新しい「いいね」カウントを返す
        return response()->json([
            'liked' => $liked,
            'likeCount' => $item->likes()->count(),
        ]);
    }
}
