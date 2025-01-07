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
     * 商品一覧を取得（メーカー情報を含む、ページネーション対応、検索機能付き）
     *
     * @param int $perPage
     * @param array $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAllProductsWithCompany($perPage = 10, array $searchParams = [])
    {
        $query = self::with('company'); // メーカー情報を含むクエリ

        // 部分一致検索（商品名）
        if (!empty($searchParams['product_name'])) {
            $query->where('product_name', 'LIKE', '%' . $searchParams['product_name'] . '%');
        }

        // 完全一致検索（メーカーID）
        if (!empty($searchParams['company_id'])) {
            $query->where('company_id', $searchParams['company_id']);
        }

        // ページネーション付きで結果を返す
        return $query->paginate($perPage);
    }

    /**
     * 新しい商品を登録
     *
     * @param array $data
     * @return Product
     */
    public static function createProduct(array $data)
    {
        $filePath = null;

        if (!empty($data['img_path']) && $data['img_path'] instanceof \Illuminate\Http\UploadedFile) {
            $filePath = $data['img_path']->store('products', 'public');
        }

        return self::create([
            'product_name' => $data['product_name'],
            'company_id' => $data['company_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'comment' => $data['comment'] ?? null,
            'img_path' => $filePath,
        ]);
    }

    /**
     * 商品情報を更新
     *
     * @param array $data
     * @return void
     */
    public function updateProduct(array $data)
    {
        // 画像の更新処理
        if (!empty($data['img_path']) && $data['img_path'] instanceof \Illuminate\Http\UploadedFile) {
            if ($this->img_path) {
                Storage::delete('public/' . $this->img_path); // 古い画像を削除
            }
            $this->img_path = $data['img_path']->store('products', 'public');
        }

        // 他の情報を更新
        $this->update([
            'product_name' => $data['product_name'],
            'company_id' => $data['company_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'comment' => $data['comment'] ?? null,
        ]);
    }
}
