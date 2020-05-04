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
            $table->string('title');
            $table->string('image_url')->nullable();
            $table->longtext('body');
            $table->bigInteger('views')->default(0);
            $table->foreignId('catagory_id_fk');
            $table->foreignid('user_id_fk');
            $table->boolean('is_published')->default(0);
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('user_id_fk')->references('id')->on('users');
            $table->foreign('catagory_id_fk')->references('id')->on('catagories');
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
