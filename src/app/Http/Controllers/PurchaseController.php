<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入画面表示
    public function purchase($item_id)
    {
        // 現在ログイン中のユーザーのプロフィール情報を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 商品情報を取得
        $item = Item::find($item_id);

        return view('purchase' , compact('item', 'profile'));
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
