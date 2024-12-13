@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">ユーザーログイン画面</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- メールアドレス -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            メールアドレス<span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- パスワード -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            パスワード<span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- ボタン -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('register') }}" class="btn btn-warning text-white">新規登録</a>
                        <button type="submit" class="btn btn-primary">ログイン</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
