@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品詳細</h1>

    <!-- 詳細表示 -->
    <div class="card">
        <div class="card-header">商品情報</div>
        <div class="card-body">
            <p><strong>商品ID:</strong> {{ $product->id }}</p>
            <p><strong>商品名:</strong> {{ $product->product_name }}</p>
            <p><strong>メーカー:</strong> {{ $product->company->company_name ?? '不明' }}</p>
            <p><strong>価格:</strong> {{ $product->price }} 円</p>
            <p><strong>在庫数:</strong> {{ $product->stock }} 個</p>
            <p><strong>コメント:</strong> {{ $product->comment ?? 'なし' }}</p>
            <p>
                <strong>商品画像:</strong><br>
                @if ($product->img_path)
                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-thumbnail"
                        style="max-height: 200px;">
                @else
                    <span>画像がありません</span>
                @endif
            </p>
        </div>
    </div>

    <!-- ボタンの配置 -->
    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </div>
</div>
@endsection