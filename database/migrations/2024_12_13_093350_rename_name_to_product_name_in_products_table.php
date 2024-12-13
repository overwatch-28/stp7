<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 'name' カラムを 'product_name' にリネーム
            if (Schema::hasColumn('products', 'name')) {
                $table->renameColumn('name', 'product_name');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 'product_name' を元の 'name' に戻す
            if (Schema::hasColumn('products', 'product_name')) {
                $table->renameColumn('product_name', 'name');
            }
        });
    }
};
