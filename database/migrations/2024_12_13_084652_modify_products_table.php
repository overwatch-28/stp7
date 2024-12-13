<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 'manufacturer' カラムが存在する場合のみリネームを実行
            if (Schema::hasColumn('products', 'manufacturer')) {
                if (!Schema::hasColumn('products', 'company_id')) {
                    $table->renameColumn('manufacturer', 'company_id');
                } else {
                    $table->dropColumn('manufacturer'); // 既存カラムが競合する場合は削除
                }
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 'company_id' を 'manufacturer' に戻す操作を条件付きで実行
            if (Schema::hasColumn('products', 'company_id')) {
                $table->renameColumn('company_id', 'manufacturer');
            }
        });
    }
};

