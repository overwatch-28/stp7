<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    // テーブル名を明示的に指定（省略可）
    protected $table = 'sales';

    // 自動増分ID
    protected $primaryKey = 'id';

    // 主キーが数値型であることを明示
    public $incrementing = true;

    // 主キーが非数値型の場合にfalseに設定
    protected $keyType = 'int';

    // タイムスタンプの自動管理
    public $timestamps = true;

    // 編集可能なカラムを指定
    protected $fillable = [
        'product_id', // 外部キー
    ];

    /**
     * products テーブルとのリレーション
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
