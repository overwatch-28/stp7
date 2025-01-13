<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 購入処理
     */
    public function purchase(Request $request)
    {
        \Log::info('Purchase endpoint hit', $request->all());
        // リクエストのバリデーション
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // 商品IDが存在することを確認
            'quantity' => 'required|integer|min:1',       // 購入数は1以上の整数
        ]);

        try {
            // トランザクション処理
            $response = DB::transaction(function () use ($validated) {
                // 商品を取得
                $product = Product::findOrFail($validated['product_id']);

                // 在庫チェック
                if ($product->stock < $validated['quantity']) {
                    throw new \Exception('在庫が不足しています。', 400);
                }

                // 販売記録を作成
                $sale = Sales::create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                ]);

                // 在庫を減算
                $product->decrement('stock', $validated['quantity']);

                return [
                    'message' => '購入が完了しました。',
                    'sale' => $sale
                ];
            });

            return response()->json($response, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}
