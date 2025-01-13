<?php

namespace App\Http\Controllers;

use App\Models\Sales; // Salesモデルに変更
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * リソースの一覧を表示
     */
    public function index()
    {
        return response()->json(Sales::all()); // Salesモデルを使用
    }

    /**
     * 新しいリソースを保存
     */
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // productsテーブルのidが存在することを確認
        ]);

        // データベースに新しい販売記録を作成
        $sale = Sales::create($validated); // Salesモデルを使用

        return response()->json([
            'message' => '販売記録が正常に作成されました',
            'data' => $sale
        ], 201);
    }

    /**
     * 指定されたリソースを表示
     */
    public function show($id)
    {
        // 指定されたIDの販売記録を取得
        $sale = Sales::findOrFail($id); // Salesモデルを使用

        return response()->json($sale);
    }

    /**
     * 指定されたリソースを更新
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // productsテーブルのidが存在することを確認
        ]);

        // 指定されたIDの販売記録を取得して更新
        $sale = Sales::findOrFail($id); // Salesモデルを使用
        $sale->update($validated);

        return response()->json([
            'message' => '販売記録が正常に更新されました',
            'data' => $sale
        ]);
    }

    /**
     * 指定されたリソースを削除
     */
    public function destroy($id)
    {
        // 指定されたIDの販売記録を取得して削除
        $sale = Sales::findOrFail($id); // Salesモデルを使用
        $sale->delete();

        return response()->json([
            'message' => '販売記録が正常に削除されました'
        ]);
    }
}
