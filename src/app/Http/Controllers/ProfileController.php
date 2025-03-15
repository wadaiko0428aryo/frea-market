<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

    // プロフィール画面を表示
    public function myPage()
    {
        // 現在ログイン中のユーザーの情報を取得
        $user = Auth::user();

        // ユーザーのプロフィールの条件に一致するデータ１件を取得
        $profile = Profile::where('user_id', Auth::id())->first();

        return view('myPage', compact('user', 'profile'));
    }

    // プロフィール編集画面を表示
    public function profile()
    {
        // 現在ログイン中のユーザー
        $user = Auth::user();

         // ユーザーのプロフィールを取得 or 作成
        $profile = Profile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['post' => '', 'address' => '', 'building' => '', 'image' => '', ],
        );

        return view('profile', compact('profile', 'user'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        // 現在ログイン中のユーザー
        $user = Auth::user();

        // ユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 画像がアップロードされた場合、保存
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Profile_images', 'public');
            $profile->image = $imagePath;
        }

        // プロフィール情報を更新
        $profile->update([
            'post' => $request->post,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // ユーザー名を更新
        $user->update([
            'name' => $request->name
        ]);

        return redirect()->route('topPage');
    }
}
