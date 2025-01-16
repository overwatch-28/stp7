@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報一覧</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-4" id="search-form">
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

        <div class="col-md-3">
            <label for="price_min" class="form-label">価格（最小）</label>
            <input type="number" name="price_min" id="price_min" class="form-control"
                value="{{ request('price_min') }}">
        </div>
        <div class="col-md-3">
            <label for="price_max" class="form-label">価格（最大）</label>
            <input type="number" name="price_max" id="price_max" class="form-control"
                value="{{ request('price_max') }}">
        </div>

        <div class="col-md-3">
            <label for="stock_min" class="form-label">在庫数（最小）</label>
            <input type="number" name="stock_min" id="stock_min" class="form-control"
                value="{{ request('stock_min') }}">
        </div>
        <div class="col-md-3">
            <label for="stock_max" class="form-label">在庫数（最大）</label>
            <input type="number" name="stock_max" id="stock_max" class="form-control"
                value="{{ request('stock_max') }}">
        </div>

        <div class="col-md-3 align-self-end">
            <label for="sort_field" class="form-label">並び順</label>
            <select name="sort_field" id="sort_field" class="form-select">
                <option value="id" {{ request('sort_field') == 'id' ? 'selected' : '' }}>ID</option>
                <option value="product_name" {{ request('sort_field') == 'product_name' ? 'selected' : '' }}>商品名</option>
                <option value="price" {{ request('sort_field') == 'price' ? 'selected' : '' }}>価格</option>
                <option value="stock" {{ request('sort_field') == 'stock' ? 'selected' : '' }}>在庫数</option>
            </select>
        </div>
        <div class="col-md-3 align-self-end">
            <label for="sort_order" class="form-label">順序</label>
            <select name="sort_order" id="sort_order" class="form-select">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>昇順</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>降順</option>
            </select>
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary" id="search-button">検索</button>
            <button type="button" class="btn btn-secondary" id="reset-button">リセット</button>
        </div>
    </form>

    <!-- 新規登録リンク -->
    <div class="mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-success">新規登録</a>
    </div>

    <!-- 商品一覧表示 -->
    <div id="product-table">
        @include('products.partials.product_table', ['products' => $products])
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            console.log('スクリプトが読み込まれました！');

            // CSRFトークンを設定
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 検索ボタンのクリックイベント
            $('#search-button').on('click', function (e) {
                e.preventDefault();

                const formData = $('#search-form').serialize();

                $.ajax({
                    url: "{{ route('products.index') }}",
                    type: 'GET',
                    data: formData,
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#product-table').html(response.html); // テーブルを更新
                            console.log('検索成功:', response);
                        } else {
                            alert('検索に失敗しました。');
                            console.log('検索失敗:', response.message);
                        }
                    },
                    error: function (xhr) {
                        alert('検索中にエラーが発生しました。もう一度お試しください。');
                        console.log('検索失敗:', xhr.responseText);
                    }
                });
            });

            // リセットボタンのクリックイベント（フォームのクリア）
            $('#reset-button').on('click', function (e) {
                e.preventDefault();
                $('#search-form')[0].reset();  // フォームをクリア
                window.location.href = "{{ route('products.index') }}"; // ページをリロード
            });

            // ページネーションのクリック時に非同期で更新
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();

                const url = $(this).attr('href');
                $.get(url, function (response) {
                    $('#product-table').html(response.html); // 商品リストを更新
                }).fail(function (xhr) {
                    alert('ページ更新中にエラーが発生しました。');
                    console.log('ページネーションエラー:', xhr.responseText);
                });
            });
        });
    </script>
@endpush