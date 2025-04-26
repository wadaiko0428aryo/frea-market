<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Mail\TokenEmail;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // バリデーションが通った後の処理
        $validated = $request->validated();  // バリデーション通過後のデータ

        // 新規ユーザー登録
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // メール認証用のトークンを生成
        $onetime_token = strval(rand(1000, 9999));  // 4桁のトークン
        $onetime_expiration = now()->addMinutes(10); // トークンの有効期限

        // トークンをユーザーに保存
        $user->onetime_token = $onetime_token;
        $user->onetime_expiration = $onetime_expiration;
        $user->save();

        // メール送信
        Mail::to($user->email)->send(new TokenEmail($user->email, $onetime_token));


        session([
            'email' => $user->email,
            'referer' => 'register',
        ]);

        return redirect()->route('mailCheck');
    }

    public function mailCheck()
    {
        return view('auth.mailCheck');
    }

    /**
     **引数で渡されたメールアドレスとワンタイムトークンをusersテーブルに追加するコントロール
     */
    public static function storeEmailAndToken($email, $onetime_token, $onetime_expiration)
    {
        User::create([
            'email' => $email,
            'onetime_token' => $onetime_token,
            'onetime_expiration' => $onetime_expiration
        ]);
    }

    /**
     **引数で渡されたワンタイムトークンをusersテーブルに追加するコントロール
     */
    public static function storeToken($email, $onetime_token, $onetime_expiration) {
        User::where('email', $email)->update([
            'onetime_token' => $onetime_token,
            'onetime_expiration' => $onetime_expiration
        ]);
    }

    /**
     **ワンタイムトークンが含まれるメールを送信する
     */
    public function sendTokenEmail(Request $request) {
        $email = $request->email;
        $onetime_token = "";

        for ($i = 0; $i < 4; $i++) {
            $onetime_token .= strval(rand(0, 9)); // ワンタイムトークン
        }
        $onetime_expiration = now()->addMinute(3); // 有効期限

        $user = User::where('email', $email)->first(); // 受け取ったメールアドレスで検索
        if ($user === null) {
            AuthController::storeEmailAndToken($email, $onetime_token, $onetime_expiration);
        } else {
            AuthController::storeToken($email, $onetime_token, $onetime_expiration);
        }

        session()->flash('email', $email); // 認証処理で利用するために一時的に格納

        Mail::to($user->email)->send(new TokenEmail($user->email, $onetime_token));

        return view("auth.second-auth");
    }

    /**
     **ワンタイムトークンが正しいか確かめてログインさせる
     */
        public function auth(Request $request)
        {
            $user = User::where('email', $request->email)->first();

            if ($user && $user->onetime_token == $request->onetime_token && now()->lessThanOrEqualTo($user->onetime_expiration)) {
                // 認証成功
                Auth::login($user);
                // ここで `referer` を取得
                $referer = session('referer', 'login'); // セッションから取得、なければ 'login'

                if ($referer === 'register') {
                    // 新規登録ユーザーの場合、プロフィール登録へ
                    return redirect()->route('profile');
                } else {
                    // ログインユーザーの場合、トップページへ
                    return redirect()->route('topPage')->with('message', 'さんのアカウントにログインしました');
                }
            }

            // トークンが無効または期限切れ
            return redirect()->route('login')->withErrors(['token' => '無効なトークンです。']);
        }





    public function login(LoginRequest $request)
    {
        // バリデーションが通った後の処理
        $validated = $request->validated();  // バリデーション通過後のデータ

        $user = User::where('email', $validated['email'])->first();

        // パスワードチェック
        if ($user && Hash::check($validated['password'], $user->password)) {

            // ワンタイムトークン生成
            $onetime_token = strval(rand(1000, 9999));
            $onetime_expiration = now()->addMinutes(10);

            // トークンを保存
            $user->onetime_token = $onetime_token;
            $user->onetime_expiration = $onetime_expiration;
            $user->save();

            // トークンメール送信
            Mail::to($user->email)->send(new TokenEmail($user->email, $onetime_token));

            // セッションに保存
            session([
                'email' => $user->email,
                'referer' => 'login',  // ここでloginって設定しておく
            ]);
            return redirect()->route('mailCheck');
        }

        // ユーザー認証
        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return redirect()->route('topPage')->with('message', 'さんのアカウントにログインしました');  // ログイン後、トップページにリダイレクト
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->withInput();  // 認証失敗時
    }
}