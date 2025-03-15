@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-view">
    <h2 class="login title">
        会員登録
    </h2>
    <div class="login-form">
        <form action="{{ route('register') }}" method="post">
            @csrf
            <div class="form_group">
                <label for="name" class="label">
                    ユーザー名
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="input">
                    <div class="error">
                        @error('name')
                        {{  $message }}
                        @enderror
                    </div>
            </div>

            <div class="form_group">
                <label for="email" class="label">
                    メールアドレス
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="input">
                <div class="error">
                        @error('email')
                        {{  $message }}
                        @enderror
                    </div>
            </div>

            <div class="form_group">
                <label for="password" class="label">
                    パスワード
                </label>
                <input type="password" name="password" id="password" value="{{ old('password') }}" class="input">
                <div class="error">
                        @error('password')
                        {{  $message }}
                        @enderror
                    </div>
            </div>

            <div class="form_group">
                <label for="password_confirmation" class="label">
                    確認用パスワード
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="input">
                <div class="error">
                    @error('password_confirmation')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="login-btn">
                <input type="submit" value="登録する" class="btn">
            </div>
            <a href="/login" class="login-link">ログインはこちら</a>
        </form>
    </div>
</div>
@endsection