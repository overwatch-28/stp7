<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 検索条件を取得
        $query = Product::query()->with('company');

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        // ページネーションで商品を取得
        $products = $query->paginate(10);

        // メーカーリストを取得
        $companies = Company::all();

        // ビューにデータを渡す
        return view('products.index', compact('products', 'companies'));
    }

    /**
     * 商品登録フォームを表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // メーカーリストを取得
        $companies = Company::all();

        // ビューにデータを渡す
        return view('products.create', compact('companies'));
    }

    /**
     * 新しい商品を登録
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 画像ファイルを保存
        $filePath = $request->hasFile('img_path')
            ? $request->file('img_path')->store('products', 'public')
            : null;

        // 商品データを保存
        Product::create([
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
            'img_path' => $filePath,
        ]);

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
    }

    /**
     * 商品編集フォームを表示
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // 編集対象の商品を取得
        $product = Product::findOrFail($id);

        // メーカーリストを取得
        $companies = Company::all();

        // ビューにデータを渡す
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * 商品情報を更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 編集対象の商品を取得
        $product = Product::findOrFail($id);

        // 画像ファイルを保存
        if ($request->hasFile('img_path')) {
            if ($product->img_path) {
                Storage::delete('public/' . $product->img_path); // 古い画像を削除
            }
            $product->img_path = $request->file('img_path')->store('products', 'public');
        }

        // 商品情報を更新
        $product->update([
            'product_name' => $request->product_name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
        ]);

        return redirect()->route('products.index')->with('success', '商品情報を更新しました。');
    }

    /**
     * 商品詳細を表示
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // 詳細を表示する対象の商品を取得
        $product = Product::with('company')->findOrFail($id);

        // ビューにデータを渡す
        return view('products.show', compact('product'));
    }

    /**
     * 商品を削除
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}
