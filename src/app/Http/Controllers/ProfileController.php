<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{

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

    // プロフィール更新機能
    public function updateProfile(ProfileRequest $request)
    {

        // 現在ログイン中のユーザー
        $user = Auth::user();

        // ユーザーのプロフィールを取得
        $profile = Profile::where('user_id', Auth::id())->first();

        // 画像の処理
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($profile->image) {
                \Storage::disk('public')->delete($profile->image);
            }
            // 新しい画像を保存
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

        // `session()->get()` でセッションの値を取得
        $redirectTo = session()->get('referer') === 'register' ? 'topPage' : 'myPage';

        return redirect()->route($redirectTo);
    }
}
