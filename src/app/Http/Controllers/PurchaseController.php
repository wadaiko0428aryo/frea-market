<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    // 商品購入画面表示
    public function purchase($item_id)
    {

        $purchase_methods = [
            'コンビニ払い',
            'カード払い',
        ];

        // プロフィールデータの「user_id」を検索し、最初の１件を「$profile」に代入
        $profile = Profile::where('user_id', Auth::id())->first();

        // 商品情報を取得
        $item = Item::find($item_id);

        return view('purchase' , compact('item', 'profile', 'purchase_methods'));
    }

    // 商品購入機能
    public function storePurchase(PurchaseRequest $request)
    {

        $item = Item::find($request->item_id);

        $profile = Profile::where('user_id', Auth::id())->first();

        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'profile_id' => optional($profile)->id, // `$profile` が `null` なら `null`
            'purchase_method' => $request->purchase_method,
        ]);


        $item->update(['is_sold' => true]);

        return redirect()->route('myPage')->with('message', '商品を購入しました');

    }

    // 住所変更画面表示
    public function address($item_id)
    {
        // 現在ログイン中のユーザーのプロフィール情報を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 商品情報を取得
        $item = Item::find($item_id);

        return view('address' , compact('item', 'profile'));
    }

    // 住所変更機能
    public function updateAddress(AddressRequest $request, $item_id)
    {
        // 現在ログイン中のユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())->firstOrFail();

        // プロフィールを更新
        $profile->update([
            'post' => $request->post,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // 購入画面にリダイレクト
        return redirect()->route('purchase', ['item_id' => $item_id]);
    }
}
