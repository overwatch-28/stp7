@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">ユーザー新規登録画面</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- ユーザー名 -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            ユーザー名<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- メールアドレス -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            メールアドレス<span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
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
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- パスワード（確認用） -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            パスワード（確認用）<span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <!-- ボタン -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">登録</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary">戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
