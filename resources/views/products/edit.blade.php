@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報編集画面</h1>

    <!-- 商品IDの固定表示 -->
    <div class="mb-3">
        <p><strong>商品ID:</strong> {{ $product->id }}</p>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_name" class="form-label">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->product_name) }}" required>
            @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                <option value="">選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">価格 <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">在庫数 <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror">{{ old('comment', $product->comment) }}</textarea>
            @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像</label>
            <input type="file" name="img_path" id="img_path" class="form-control @error('img_path') is-invalid @enderror">
            @if ($product->img_path)
                <p>現在の画像: <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-thumbnail" style="max-height: 150px;"></p>
            @endif
            @error('img_path')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-warning">更新</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
    </form>
</div>
@endsection
