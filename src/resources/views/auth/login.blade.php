@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login-view">
        <h2 class="login title">
            ログイン
        </h2>
        <div class="login-form">
            <form action="{{ route('login') }}" method="post">
            @csrf
                <div class="form_group">
                    <label for="email" class="label">
                        メールアドレス
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="input">
                    <div class="error">
                        @error('email')
                        <p>{{ $message }}</p>
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
                <div class="login-btn">
                    <input type="submit" value="ログインする" class="btn">
                </div>
                <a href="/register" class="login-link">会員登録はこちら</a>
            </form>
        </div>
    </div>
@endsection