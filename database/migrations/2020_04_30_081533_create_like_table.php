<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignid('user_id_fk');
            $table->foreignid('article_id_fk');
            $table->enum('react',['LIKE','DISLIKE']);
            $table->timestamps();
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->unique(['user_id_fk','article_id_fk']);
            $table->foreign('user_id_fk')->references('id')->on('users');
            $table->foreign('article_id_fk')->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('like');
    }
}
