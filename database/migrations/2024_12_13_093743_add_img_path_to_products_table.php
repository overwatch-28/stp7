<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // img_path カラムを追加
            $table->string('img_path')->nullable()->after('comment');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // img_path カラムを削除
            $table->dropColumn('img_path');
        });
    }
};
