<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComentTable4columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coments', function (Blueprint $table) {
            //
            $table->boolean('is_read')->nullable()->after('content');
            $table->boolean('is_hold')->nullable()->after('content');
            $table->boolean('is_trading')->nullable()->after('content');
            $table->string('image')->nullable()->after('content'); //確認！(image,255)?長さ指定は？？
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coments', function (Blueprint $table) {
            $table->dropColumn('is_read');
            $table->dropColumn('is_hold');
            $table->dropColumn('is_trading');
            $table->dropColumn('image');
        });
    }
}
