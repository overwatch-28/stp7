<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * モデルで操作可能なカラム（ホワイトリスト）
     */
    protected $fillable = [
        'company_id',     // 外部キー
        'product_name',   // 商品名
        'price',          // 価格
        'stock',          // 在庫数
        'comment',        // コメント
        'img_path',       // 画像パス
    ];

    /**
     * リレーション：Productは1つのCompanyに属する
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * リレーション：Productは複数のSaleを持つ
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
