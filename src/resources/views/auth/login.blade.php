@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="login-view">
        <div class="login-form">
            <h2 class="login-title">
                ログイン
            </h2>

            <form action="{{ route('login') }}" method="post">
            @csrf

                <div class="form-group">
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

                <div class="form-group">
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
                <div class="login-link">
                    <a href="/register" class="link">会員登録はこちら</a>
                </div>
            </form>
        </div>
    </div>
@endsection