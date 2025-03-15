@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('content')
    <div class="profile-edit_content">

        <h2 class="profile-edit_title">
            プロフィール設定
        </h2>


        <form action="{{ route('updateProfile') }}" method="post">
        @csrf
            <div class="profile-img">
                <img src="" alt="プロフィール画像" class="profile-img_icon">
                <input type="file" name="image" class="profile-img_select">
                </div>
            </div>
            <div class="user-information">

                <div class="user-edit_group">
                    <label for="name" class="label">
                        ユーザー名
                    </label>
                    <input type="text" name="name" value="{{ old('name') ?? $user->name }}" class="user-edit_input">
                    <div class="error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="user-edit_group">
                    <label for="post" class="label">
                        郵便番号
                    </label>
                    <input type="text" name="post" value="{{ old('post') ?? $profile->post }}" class="user-edit_input">
                    <div class="error">
                        @error('post')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="user-edit_group">
                    <label for="address" class="label">
                        住所
                    </label>
                    <input type="text" name="address" value="{{ old('address') ?? $profile->address }}" class="user-edit_input">
                    <div class="error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="user-edit_group">
                    <label for="building" class="label">
                        建物
                    </label>
                    <input type="text" name="building" value="{{ old('building') ?? $profile->building }}" class="user-edit_input">
                    <div class="error">
                        @error('building')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

            </div>
            <div class="update-btn">
                <input type="submit" value="更新する" class="update-submit">
            </div>
        </form>

    </div>
@endsection