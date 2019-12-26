<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->string('id')->primary()->comment('tweetId');
            $table->text('content')->comment('tweet内容');
            $table->time('hour')->comment('時間');
            $table->unsignedInteger('activity_id');
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')->on('activities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweets');
    }
}
