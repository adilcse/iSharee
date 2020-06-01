<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug',255)->unique();
            $table->string('title',100);
            $table->string('image_url',500)->nullable();
            $table->boolean('allow_image_as_slider')->default(1);
            $table->longtext('body');
            $table->bigInteger('views')->default(0);
            $table->foreignid('user_id');
            $table->boolean('is_published')->default(0);
            $table->boolean('paid')->default(0);
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
