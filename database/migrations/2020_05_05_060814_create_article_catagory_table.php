<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCatagoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_catagory', function (Blueprint $table) {
            $table->foreignId('article_id');
            $table->foreignId('catagory_id');
        });
        Schema::table('article_catagory', function (Blueprint $table) {
            $table->primary(['article_id','catagory_id']);
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('catagory_id')->references('id')->on('catagories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_catagory');
    }
}
