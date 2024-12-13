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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->string('company_name'); // 会社名
            $table->string('street_address')->nullable(); // 住所（任意）
            $table->string('representative_name')->nullable(); // 代表者名（任意）
            $table->timestamps(); // 作成日時、更新日時
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }

};
