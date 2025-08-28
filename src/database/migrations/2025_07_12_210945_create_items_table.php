<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 255);
            $table->string('brand', 255)->nullable();
            $table->Integer('price');
            $table->text('detail');
            $table->string('img', 255);
            $table->foreignId('condition_id')->constrained();
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            $table->tinyInteger('payment_method')->nullable();
            $table->string('post_code', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building', 255)->nullable();
            $table->tinyInteger('sold');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
