<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    /**
     * モデルで操作可能なカラム（ホワイトリスト）
     */
    protected $fillable = [
        'product_id', // 外部キー（Productに紐づく）
    ];

    /**
     * リレーション：Saleは1つのProductに属する
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
