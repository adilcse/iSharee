<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('email',50)->unique();
            $table->string('mobile',10)->unique()->nullable();
            $table->boolean('is_email_verified')->default('0');
            $table->boolean('is_mobile_verified')->default('0');
            $table->boolean('is_admin')->default('0');
            $table->boolean('is_active')->default('1');
            $table->string('password');
            $table->rememberToken();
            $table->string('oauth_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
