<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
            // モデルのメソッドを呼び出して処理を委譲
            $response = Product::processPurchase($validated['product_id'], $validated['quantity']);

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}
