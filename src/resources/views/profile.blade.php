@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-edit_view">
    <div class="profile-form">
        <div class="profile-edit_form">

            <form action="{{ route('updateProfile') }}" method="post" id="imageUploadForm" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="referer" value="{{ session('referer', 'myPage') }}">

                <h2 class="profile-edit_title">
                    プロフィール設定
                </h2>

                <div class="user-edit_group">
                    <div class="profile-img_container">
                        <div class="profile-img">
                            <img src="{{ asset('storage/' . $profile->image) }}" alt="" id="previewImage" class="profile-image">
                        </div>
                        <label for="imageInput" class="image-select_btn">画像を選択する
                        </label>
                        <input type="file" id="imageInput"  name="image" class="profile-img_input" accept="image/*">
                        <div class="file-name" id="fileName"></div>

                        <div class="error">
                            @error('image')
                                {{ $message }}
                            @enderror
                        </div>
                        </div>
                    </div>

                </div>

                <div class="user-edit_group">
                    <label for="name" class="label">
                        ユーザー名
                    </label>
                    <input type="text" name="name" value="{{ old('name') ?? $user->name }}" class="input">
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
                    <input type="text" name="post" value="{{ old('post') ?? $profile->post }}" class="input">
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
                    <input type="text" name="address" value="{{ old('address') ?? $profile->address }}" class="input">
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
                    <input type="text" name="building" value="{{ old('building') ?? $profile->building }}" class="input">
                    <div class="error">
                        @error('building')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="update-btn">
                    <input type="submit" value="更新する" class="btn">
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('imageInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('previewImage');
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection