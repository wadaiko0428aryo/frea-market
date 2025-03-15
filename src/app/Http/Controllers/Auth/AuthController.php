<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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

        // ログイン処理
        Auth::login($user);

        return redirect()->route('profile');  // プロフィール編集画面にリダイレクト
    }

    public function login(LoginRequest $request)
    {
        // バリデーションが通った後の処理
        $validated = $request->validated();  // バリデーション通過後のデータ

        // ユーザー認証
        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return redirect()->route('topPage');  // ログイン後、トップページにリダイレクト
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->withInput();  // 認証失敗時
    }
}