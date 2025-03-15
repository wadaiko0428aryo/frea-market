@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-content">
    <h2 class="address-change">
        住所の変更
    </h2>
    <form action="{{ route('updateAddress', ['item_id' => $item->id]) }}" method="post">
    @csrf

        <div class="address-group">
            <label for="post" class="address-label">
                郵便番号
            </label>
            <input type="text" name="post" id="post" value="{{ old('post') ??  $profile->post }}" class="address-input">
            <div class="error">
                @error('post')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="address-group">
            <label for="post" class="address-label">
                住所
            </label>
            <input type="text" name="address" id="address" value="{{ old('address') ?? $profile->address }}" class="address-input">
            <div class="error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="address-group">
            <label for="post" class="address-label">
                建物名
            </label>
            <input type="text" name="building" id="building" value="{{ old('building') ?? $profile->building }}" class="address-input">
        </div>

        <div class="address-update">
            <input type="submit" class="address-update_btn" value="更新する">
        </div>

    </form>
</div>
@endsection