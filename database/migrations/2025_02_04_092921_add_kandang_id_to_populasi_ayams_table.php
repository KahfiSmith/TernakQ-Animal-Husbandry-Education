<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('populasi_ayam', function (Blueprint $table) {
            $table->unsignedBigInteger('kandang_id')->nullable()->after('status_ayam');
            $table->foreign('kandang_id')->references('id')->on('kandang_ayam')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('populasi_ayam', function (Blueprint $table) {
            $table->dropForeign(['kandang_id']);
            $table->dropColumn('kandang_id');
        });
    }
};
