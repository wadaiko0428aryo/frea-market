<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // トップページ表示（ログイン有無可）
    public function index(Request $request)
    {
        $user = Auth::user();

        $page = $request->query('page');  //クエリパラメータ取得//

        // ログインしている場合は、自分の商品を除外（user_id が null のデータは表示）
        if (Auth::check()) {
            $items = Item::where(function ($query) {
                $query->where('user_id', '!=', Auth::id())
                ->orWhereNull('user_id');
            })->paginate(8);
        } else {
            // すべての商品を取得
            $items = Item::paginate(8);
        }

        return view('index' , compact('items' , 'page', 'user'));
    }

    // ログインアラート表示
    public function nothing()
    {
        return view('nothing');
    }


    // マイリスト画面表示
    public function myList()
    {
        $likedItems = Item::whereHas('likes', function ($query) {
            $query->where('user_id', Auth::id());
        })->paginate(8);

        return view('myList', compact('likedItems'));
    }


    // 商品名部分一致検索機能
    public function search(Request $request)
    {
        $query = Item::query();
        $keyword = $request->input('name'); //検索キーワードを取得

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        } // 名前による部分一致検索

        $items = $query->paginate(8);  // ページネーション付きで結果を取得

        return view('index', compact('items', 'keyword')); // 検索結果をビューに渡す
    }

    // 詳細画面表示
    public function detail($item_id)
    {
        // 現在ログイン中のユーザーの情報を取得
        $user = Auth::user();

        $categories = Category::all();

        $item = Item::find($item_id);

        // ユーザーのプロフィールの条件に一致するデータ１件を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        return view('detail' , compact('item', 'categories','profile','user'));
    }


    // コメント機能
    public function comment($itemId)
    {
        // コメントとユーザー情報を取得
        $item = Item::with('comments.user')->find($itemId);
        return view ('detail', compact('item'));
    }

    // コメント登録機能
    public function storeComment(CommentRequest $request, $itemId)
    {
        $item = Item::find($itemId);
        $comment = new Comment();
        $comment->item_id = $item->id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->route('detail', ['item_id' =>$itemId]);
    }


    // マイページを表示（出品した画面を表示）
    public function myPage()
    {
        // 現在ログイン中のユーザーの情報を取得
        $user = Auth::user();

        // 出品した商品をページネーション付きで取得
        $items = Item::where('user_id', Auth::id())->paginate(8);

        // ユーザーのプロフィールの条件に一致するデータ１件を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        return view('myPage', compact('user','items', 'profile'));
    }


    // 購入した画面を表示
    public function purchaseList()
    {
        // 現在ログイン中のユーザー情報を取得
        $user = Auth::user();

        // プロフィールデータのユーザーIDを検索し、最初の１件を「$profile」に代入
        $profile = Profile::where('user_id', Auth::id())->first();

        // 購入した商品を取得
        $purchasedItems = Purchase::with('item')->where('user_id', auth()->id())->paginate(8);

        return view('purchaseList', compact('user', 'profile', 'purchasedItems'));
    }



}
