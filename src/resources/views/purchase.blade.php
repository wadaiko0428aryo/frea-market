@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<form class="purchase-form" action="{{ route('storePurchase', ['item_id' => $item->id]) }}" method="post">
    @csrf

    <div class="purchase-left">

        <div class="purchase-img_card">
            <div class="purchase-img">
                <img src="{{ Str::startsWith($item->image, 'items/') ? asset('storage/' . $item->image) : asset('images/' . basename($item->image)) }}" alt="商品画像" class="purchase-item_img">
            </div>
            <div class="purchase-box">
            <div class="purchase-name">
                {{ $item->name }}
            </div>
            <div class="purchase-price">
                ¥ <span class="purchase-price_data">{{ $item->price }}</span>
            </div>
            </div>

        </div>

        <div class="purchase-method purchase-group">
            <div class="purchase-label">
                支払い方法
            </div>
            <div class="purchase-input">
                <select name="purchase_method" id="purchase_method" class="purchase-method_select">
                    <option value="">選択してください</option>
                    @foreach($purchase_methods as $purchase_method)
                        <option value="{{ $purchase_method }}" {{ old('purchase_method') == $purchase_method ? 'selected="selected"' : '' }}>{{ $purchase_method }}</option>
                    @endforeach
                </select>
                <div class="error">
                    @error('purchase_method')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>


        <div class="purchase-address purchase-group">
            <div class="purchase-address_data">
                <div class="purchase-label">
                    配送先
                </div>
                <div class="purchase-input purchase-input_address">
                    <div class="post-data">
                        〒<input type="text" name="post" id="post" readonly  value="{{ $profile->post }}" class="profile-data">
                    </div>
                    <div class="address-data">
                        <input type="text" name="address" id="address" readonly value="{{ $profile->address }}" class="profile-data">
                        <input type="text" name="building" id="building" readonly value="{{ $profile->building }}" class="profile-data">
                    </div>
                </div>
            </div>

            <a href="{{ route('address' , ['item_id' => $item->id]) }}" class="address-change_link">
                変更する
            </a>

        </div>
    </div>

    <div class="purchase-right">
        <div class="select-result">
            <div class="select-result_group">
                <span class="result-label">商品代金</span>
                <span class="purchase-price">
                    ¥ <span class="purchase-price_data">{{ $item->price }}</span>
                </span>
            </div>
            <div class="select-result_group">
                <span class="result-label">支払い方法</span>
                <span class="result-value" id="selected-payment">選択してください</span>
            </div>
        </div>
        
        <div class="purchase-btn">
            <button class="purchase-submit" type="submit">購入する</button>
            <a href="{{ route('detail', $item->id) }}" class="back-btn">詳細画面に戻る</a>
        </div>

    </div>

</form>

<script>
    document.getElementById('purchase_method').addEventListener('change', function() {
        const selectedMethod = this.options[this.selectedIndex].text;
        document.getElementById('selected-payment').innerText = selectedMethod;
    });
</script>

@endsection