<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        // 検索条件を取得
        $searchParams = $request->only(['product_name', 'company_id']);

        // 商品一覧を取得（関連するメーカー情報も含む）
        $products = Product::getAllProductsWithCompany(10, $searchParams); // 1ページ10件表示

        // メーカー一覧を取得
        $companies = Company::all();

        // ビューにデータを渡す
        return view('products.index', compact('products', 'companies', 'searchParams'));
    }

    /**
     * 商品登録フォームを表示
     */
    public function create()
    {
        // メーカーリストを取得
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * 新しい商品を登録
     */
    public function store(ProductRequest $request)
    {
        // バリデーション済みデータを取得して商品を登録
        Product::createProduct($request->validated());

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
    }

    /**
     * 商品詳細を表示
     */
    public function show($id)
    {
        // 詳細を表示する対象の商品を取得
        $product = Product::with('company')->findOrFail($id);

        // ビューにデータを渡す
        return view('products.show', compact('product'));
    }

    /**
     * 商品編集フォームを表示
     */
    public function edit($id)
    {
        // 編集対象の商品を取得
        $product = Product::findOrFail($id);

        // メーカーリストを取得
        $companies = Company::all();

        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * 商品情報を更新
     */
    public function update(ProductRequest $request, $id)
    {
        // 更新対象の商品を取得
        $product = Product::findOrFail($id);

        // バリデーション済みデータを取得して更新
        $product->updateProduct($request->validated());

        return redirect()->route('products.index')->with('success', '商品情報を更新しました。');
    }

    /**
     * 商品を削除
     */
    public function destroy($id)
    {
        // 削除対象の商品を取得して削除
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}
