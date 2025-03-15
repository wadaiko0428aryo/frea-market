<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // トップページ表示（ログイン有無可）
    public function index(Request $request)
    {
        $page = $request->query('page');  //クエリパラメータ取得//

        if (Auth::check() && $page === 'myList') {
            // ログインユーザーの出品商品を取得
            $items = Item::where('user_id', Auth::id())->paginate(8);
        } else {
            // すべての商品を取得
            $items = Item::paginate(8);
        }

        return view('index' , compact('items' , 'page'));
    }

    // ログインアラート表示
    public function nothing()
    {
        return view('nothing');
    }

    // マイリスト画面表示
    public function myList()
    {
        return view('myList');
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
        $categories = Category::all();
        $item = Item::find($item_id);
        return view('detail' , compact('item', 'categories'));
    }


    // 購入した画面を表示
    public function purchaseList()
    {
        return view('purchaseList');
    }




}
