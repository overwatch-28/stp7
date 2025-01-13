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
                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="width: 50px; height: 50px;">
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
                    <button class="btn btn-danger btn-sm btn-delete"
                        data-url="{{ route('products.destroy', $product->id) }}">削除</button>
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

<script>
    $(document).ready(function () {
        // 削除ボタンの非同期処理
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            if (!confirm('本当に削除しますか？')) return;

            const url = $(this).data('url');
            const row = $(this).closest('tr');

            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (response) {
                    alert(response.message || '削除が成功しました。');
                    row.remove();
                },
                error: function (xhr) {
                    alert('削除に失敗しました。もう一度お試しください。');
                    console.log('削除エラー:', xhr.responseText);
                }
            });
        });

        // ページネーションリンクのクリック時に非同期で更新
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();

            const url = $(this).attr('href');
            $.get(url, function (response) {
                $('#product-table').html(response.html); // テーブルを更新
            }).fail(function (xhr) {
                alert('ページ更新中にエラーが発生しました。');
                console.log('ページネーションエラー:', xhr.responseText);
            });
        });
    });
</script>
