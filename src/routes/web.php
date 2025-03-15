<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Auth\AuthController;

Route::post('/login' , [AuthController::class, 'login'])->name('login');
Route::post('/register' , [AuthController::class, 'register'])->name('register');

// トップページの表示（ログイン有無可）
Route::get('/' , [ItemController::class , 'index'])->name('topPage');

// 名前検索（ログイン有無可）
Route::get('/search' , [ItemController::class , 'search'])->name('search');

// 商品詳細ページの表示（ログイン有無可）
Route::get('/{item_id}/detail' , [ItemController::class , 'detail'])->name('detail');

// ログインアラートぺ-ジの表示
Route::get('/nothing', [ItemController::class, 'nothing'])->name('nothing');

// ログインが必要なルート
Route::middleware('auth')->group(function() {

    // マイリスト画面表示
    Route::get('/myList', [ItemController::class, 'myList'])->name('myList');

    // プロフィール画面の表示
    Route::get('/myPage' , [ProfileController::class , 'myPage'])->name('myPage');

    // 購入した商品画面表示
    Route::get('/myPage/purchaseList', [ItemController::class, 'purchaseList'])->name('purchaseList');




    // プロフィール編集画面を表示
    Route::get('/myPage/profile' , [ProfileController::class , 'profile'])->name('profile');

    // プロフィール更新機能
    Route::post('/myPage/profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');



    // 商品出品画面を表示
    Route::get('/mypage/sell' , [SellController::class , 'sell'])->name('sell');

    // 商品出品機能
    Route::post('/mypage/sell' , [SellController::class , 'storeSell'])->name('storeSell');



    // 商品購入画面
    Route::get('/{item_id}/purchase' , [PurchaseController::class , 'purchase'])->name('purchase');

     // 住所変更画面
    Route::get('/{item_id}/purchase/address' , [PurchaseController::class , 'address'])->name('address');

     // 住所変更機能
    Route::post('/{item_id}/purchase/address' , [PurchaseController::class , 'updateAddress'])->name('updateAddress');

});
