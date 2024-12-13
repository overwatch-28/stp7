<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // manufacturer カラムが存在する場合に削除
            if (Schema::hasColumn('products', 'manufacturer')) {
                $table->dropColumn('manufacturer');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // manufacturer カラムを再作成（元に戻す処理）
            $table->string('manufacturer')->nullable();
        });
    }
};
