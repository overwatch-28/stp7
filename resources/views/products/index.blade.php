@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報一覧</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="product_name" class="form-label">商品名</label>
            <input type="text" name="product_name" id="product_name" class="form-control"
                value="{{ request('product_name') }}">
        </div>

        <div class="col-md-3">
            <label for="company_id" class="form-label">メーカー名</label>
            <select name="company_id" id="company_id" class="form-select">
                <option value="">すべて</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">検索</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">リセット</a>
        </div>
    </form>

    <!-- 新規登録リンク -->
    <div class="mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-success">新規登録</a>
    </div>

    <!-- 商品一覧表示 -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像"
                                style="width: 50px; height: 50px;">
                        @else
                            画像なし
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">詳細表示</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">商品が登録されていません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ページネーション -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection