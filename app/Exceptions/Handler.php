<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * 特定の例外タイプに対応するカスタムログレベルのリスト
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * レポートしない例外タイプのリスト
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * バリデーション例外時にセッションにフラッシュしない入力のリスト
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * アプリケーション用の例外ハンドリングコールバックを登録
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // ここにカスタム例外レポート処理を記述
        });
    }

    /**
     * アプリケーションの例外レンダリングをカスタマイズ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // APIリクエストの場合はJSON形式のエラーレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $exception->getMessage(), // エラーメッセージをJSON形式で返す
            ], $this->isHttpException($exception) ? $exception->getStatusCode() : 500); // ステータスコードを設定
        }

        // 通常のHTMLレスポンスを返す
        return parent::render($request, $exception);
    }
}
