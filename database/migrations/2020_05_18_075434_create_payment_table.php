<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('article_id');
            $table->double('amount');
            $table->string('payment_method');
            $table->string('payment_id');
            $table->string('txn_id');
            $table->string('receipt_url');
            $table->boolean('status');
            $table->timestamps();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles');
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
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
