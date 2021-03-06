<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('id')->primary()->comment('twitterのユーザーId');
            $table->string('name')->index()->comment('画面表示名');
            $table->string('nickname')->default(null)->index()->comment('@名');
            $table->string('avatar')->comment('アイコンのurl');
            $table->string('token')->comment('token');
            $table->string('token_secret')->comment('secret token');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
