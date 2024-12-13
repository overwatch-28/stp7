<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * モデルで操作可能なカラム（ホワイトリスト）
     */
    protected $fillable = [
        'company_name',        // 会社名
        'street_address',      // 住所
        'representative_name', // 代表者名
    ];

    /**
     * リレーション：Companyは複数のProductを持つ
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
