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
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;

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
        DB::beginTransaction();

        try {
            $item = Item::findOrFail($request->item_id);
            $profile = Profile::where('user_id', Auth::id())->firstOrFail();

            // 商品がすでに購入済みなら処理中止
            if ($item->is_sold) {
                return redirect()->route('purchase.error')->with('message', 'この商品はすでに購入されています。');
            }

            // 価格チェック
            if ($item->price === null || !is_numeric($item->price)) {
                return redirect()->route('purchase.error')->with('message', '商品の価格情報に問題があります。');
            }

            // 1. まず購入登録
            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'purchase_method' => $request->purchase_method,
                'profile_id' => $profile->id,

            ]);

            // 商品の状態更新
            $item->update(['is_sold' => true]);

            // Stripe APIキー設定
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $unit_amount = (int) $item->price;

            // 2. Stripe Checkout セッション作成
            $session = Session::create([
                'payment_method_types' => [$request->purchase_method === 'カード払い' ? 'card' : 'konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $unit_amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.cancel'),
            ]);

            // Optional: payment_intent_id を保存しておくとWebhookで使える
            $purchase->update(['payment_intent_id' => $session->payment_intent ?? null]);

            DB::commit();

            return redirect($session->url);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('購入処理エラー: ' . $e->getMessage());
            return redirect()->route('purchase.error')->with('message', '購入処理中にエラーが発生しました。');
        }
    }
    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        // セッションIDを使用して、Stripe APIを通じて決済情報を取得することができます。

        return view('purchase.success', compact('session_id'));
    }
    public function cancel(Request $request)
    {
        $item = Item::find($request->item_id);
        return view('purchase.cancel', compact('item'));
    }


        public function handleWebhook(Request $request)
    {
        \Log::info('Webhook受信', $request->all());

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                env('STRIPE_WEBHOOK_SECRET') // .env で設定
            );

            if ($event->type === 'payment_intent.succeeded') {
                $paymentIntent = $event->data->object;

                // ここで支払い完了処理を行う
                Purchase::where('payment_intent_id', $paymentIntent->id)
                    ->update(['status' => 'paid']);

                \Log::info("支払い成功: " . $paymentIntent->id);
            }
        } catch (\Exception $e) {
            \Log::error("Webhookエラー: " . $e->getMessage());
            return response()->json(['error' => 'Webhook処理エラー'], 400);
        }

        return response()->json(['message' => 'OK']);
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
