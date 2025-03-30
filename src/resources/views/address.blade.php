@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <div class="address-view">
        <div class="address-form">
            <h2 class="address-title">
                住所の変更
            </h2>

            <form action="{{ route('updateAddress', ['item_id' => $item->id]) }}" method="post">
            @csrf

                <div class="form-group">
                    <label for="post" class="label">
                        郵便番号
                    </label>
                    <input type="text" name="post" id="post" value="{{ old('post') ??  $profile->post }}" class="input">
                    <div class="error">
                        @error('post')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="post" class="label">
                        住所
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address') ?? $profile->address }}" class="input">
                    <div class="error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="post" class="label">
                        建物名
                    </label>
                    <input type="text" name="building" id="building" value="{{ old('building') ?? $profile->building }}" class="input">
                </div>

                <div class="address-update">
                    <input type="submit" class="btn" value="更新する">
                </div>

            </form>
        </div>

    </div>
@endsection