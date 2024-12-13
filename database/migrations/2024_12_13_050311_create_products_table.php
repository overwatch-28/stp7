<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // 商品ID
            $table->string('name'); // 商品名
            $table->string('manufacturer')->nullable(); // メーカー名（省略可能）
            $table->integer('price'); // 価格
            $table->integer('stock'); // 在庫数
            $table->text('comment')->nullable(); // コメント（省略可能）
            $table->timestamps(); // 作成日・更新日
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
