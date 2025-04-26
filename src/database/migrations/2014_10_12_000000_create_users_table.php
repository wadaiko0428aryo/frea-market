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
            $table->varchar('name');
            $table->varchar('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->varchar('password');
            $table->rememberToken();
            $table->char("onetime_token", 4)->nullable(); // ワンタイムトークン
            $table->dateTime("onetime_expiration")->nullable(); // ワンタイムトークンの有効期限
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
