<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    /**
     * リレーション: Product belongs to Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 商品一覧を取得（メーカー情報を含む、ページネーション対応、検索・ソート機能付き）
     *
     * @param int $perPage
     * @param array $searchParams
     * @param string $sortField
     * @param string $sortOrder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAllProductsWithCompany(
        $perPage = 10,
        array $searchParams = [],
        $sortField = 'id',
        $sortOrder = 'asc'
    ) {
        $query = self::with('company'); // メーカー情報を含むクエリ

        // 部分一致検索（商品名）
        if (!empty($searchParams['product_name'])) {
            $query->where('product_name', 'LIKE', '%' . $searchParams['product_name'] . '%');
        }

        // 完全一致検索（メーカーID）
        if (!empty($searchParams['company_id'])) {
            $query->where('company_id', $searchParams['company_id']);
        }

        // 価格の範囲検索
        if (!empty($searchParams['price_min'])) {
            $query->where('price', '>=', $searchParams['price_min']);
        }
        if (!empty($searchParams['price_max'])) {
            $query->where('price', '<=', $searchParams['price_max']);
        }

        // 在庫数の範囲検索
        if (!empty($searchParams['stock_min'])) {
            $query->where('stock', '>=', $searchParams['stock_min']);
        }
        if (!empty($searchParams['stock_max'])) {
            $query->where('stock', '<=', $searchParams['stock_max']);
        }

        // ソート処理
        $validSortFields = ['id', 'product_name', 'price', 'stock', 'company_id'];
        if (in_array($sortField, $validSortFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        // ページネーション付きで結果を返す
        return $query->paginate($perPage);
    }
}
