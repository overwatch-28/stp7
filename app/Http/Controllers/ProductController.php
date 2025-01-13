<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        try {
            // 検索条件とソート条件を取得
            $searchParams = $request->only([
                'product_name',
                'company_id',
                'price_min',
                'price_max',
                'stock_min',
                'stock_max',
                'sort_field',
                'sort_order'
            ]);

            // 許可されたソートフィールドと順序
            $allowedSortFields = ['id', 'product_name', 'price', 'stock'];
            $allowedSortOrders = ['asc', 'desc'];

            $sortField = $request->get('sort_field', 'id'); // デフォルトソートフィールド
            $sortOrder = $request->get('sort_order', 'asc'); // デフォルトは昇順

            // ソートフィールドと順序の検証
            $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'id';
            $sortOrder = in_array($sortOrder, $allowedSortOrders) ? $sortOrder : 'asc';

            // 商品一覧を取得
            $products = Product::getAllProductsWithCompany(10, $searchParams, $sortField, $sortOrder);

            // 非同期リクエストの場合
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'html' => view('products.partials.product_table', compact('products'))->render()
                ]);
            }

            // メーカー一覧を取得
            $companies = Company::all();

            // 通常のビューを返す
            return view('products.index', compact('products', 'companies', 'searchParams', 'sortField', 'sortOrder'));
        } catch (\Exception $e) {
            Log::error('商品一覧の取得に失敗しました: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'エラーが発生しました'
                ], 500);
            }
            return back()->with('error', '商品一覧を取得できませんでした。');
        }
    }

    /**
     * 商品登録フォームを表示
     */
    public function create()
    {
        try {
            $companies = Company::all();
            return view('products.create', compact('companies'));
        } catch (\Exception $e) {
            Log::error('商品登録フォームの表示に失敗しました: ' . $e->getMessage());
            return back()->with('error', 'フォームの表示に失敗しました。');
        }
    }

    /**
     * 新しい商品を登録
     */
    public function store(ProductRequest $request)
    {
        try {
            Product::create($request->validated());
            return redirect()->route('products.index')->with('success', '商品が登録されました。');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('商品登録に失敗しました: ' . $e->getMessage());
            return back()->with('error', '商品登録に失敗しました。')->withInput();
        }
    }

    /**
     * 商品詳細を表示
     */
    public function show($id)
    {
        try {
            $product = Product::with('company')->findOrFail($id);
            return view('products.show', compact('product'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', '指定された商品は存在しません。');
        } catch (\Exception $e) {
            Log::error('商品詳細の表示に失敗しました: ' . $e->getMessage());
            return back()->with('error', '商品詳細の表示に失敗しました。');
        }
    }

    /**
     * 商品編集フォームを表示
     */
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $companies = Company::all();
            return view('products.edit', compact('product', 'companies'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', '指定された商品は存在しません。');
        } catch (\Exception $e) {
            Log::error('商品編集フォームの表示に失敗しました: ' . $e->getMessage());
            return back()->with('error', 'フォームの表示に失敗しました。');
        }
    }

    /**
     * 商品情報を更新
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->validated());
            return redirect()->route('products.index')->with('success', '商品情報を更新しました。');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', '指定された商品は存在しません。');
        } catch (\Exception $e) {
            Log::error('商品情報の更新に失敗しました: ' . $e->getMessage());
            return back()->with('error', '更新処理に失敗しました。');
        }
    }

    /**
     * 商品を削除
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => '商品を削除しました。'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => '指定された商品は存在しません。'], 404);
        } catch (\Exception $e) {
            Log::error('商品削除に失敗しました: ' . $e->getMessage());
            return response()->json(['message' => '削除処理に失敗しました。'], 500);
        }
    }
}
