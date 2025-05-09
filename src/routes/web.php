<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Auth\AuthController;


Route::post('/login' , [AuthController::class, 'login'])->name('login');

Route::post('/register' , [AuthController::class, 'register'])->name('register');

Route::post('sendTokenEmail', [AuthController::class, 'sendTokenEmail'])->name('sendTokenEmail');

Route::get('/auth/{email}/{onetime_token}', [AuthController::class, 'auth'])->name('auth.auth');

Route::get('mailCheck', [AuthController::class, 'mailCheck'])->name('mailCheck');


Route::post('/stripe/webhook', [purchaseController::class, 'handleWebhook']);

// トップページの表示（ログイン有無可）
Route::get('/' , [ItemController::class , 'index'])->name('topPage');

// 名前検索（ログイン有無可）
Route::get('/search' , [ItemController::class , 'search'])->name('search');

// 商品詳細ページの表示（ログイン有無可）
Route::get('/{item_id}/detail' , [ItemController::class , 'detail'])->name('detail');

// コメント機能
Route::post('/{item_id}/detail/comment', [ItemController::class, 'storeComment'])->name('item.comment.store');

// ログインアラートぺ-ジの表示
Route::get('/nothing', [ItemController::class, 'nothing'])->name('nothing');



// ログインが必要なルート
Route::middleware('auth')->group(function() {

    // マイリスト画面表示
    Route::get('/myList', [ItemController::class, 'myList'])->name('myList');

    // マイページの表示
    Route::get('/myPage' , [ItemController::class , 'myPage'])->name('myPage');

    // 購入した商品画面表示
    Route::get('/myPage/purchaseList', [ItemController::class, 'purchaseList'])->name('purchaseList');

    // いいね機能
    Route::post('/{item_id}/detail/like/toggle', [LikeController::class, 'toggleLike'])->name('toggleLike');


    // プロフィール編集画面を表示
    Route::get('/myPage/profile' , [ProfileController::class , 'profile'])->name('profile');

    // プロフィール更新機能
    Route::post('/myPage/profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');



    // 商品出品画面を表示
    Route::get('/sell' , [SellController::class , 'sell'])->name('sell');

    // 商品出品機能
    Route::post('/sell' , [SellController::class , 'storeSell'])->name('storeSell');



    // 商品購入画面
    Route::get('/{item_id}/purchase' , [PurchaseController::class , 'purchase'])->name('purchase');
    // 商品購入登録機能
    Route::post('/{item_id}/purchase' , [PurchaseController::class , 'storePurchase'])->name('storePurchase');

    // stripe
    Route::get('/purchase/success', [PurchaseController::class, 'purchase.success'])->name('purchase.success');
    Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');
    Route::get('/purchase/error', function () {
        return view('purchase.error');
    })->name('purchase.error');


     // 住所変更画面
    Route::get('/{item_id}/purchase/address' , [PurchaseController::class , 'address'])->name('address');

     // 住所変更機能
    Route::post('/{item_id}/purchase/address' , [PurchaseController::class , 'updateAddress'])->name('updateAddress');
});
